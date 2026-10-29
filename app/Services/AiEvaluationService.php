<?php

namespace App\Services;

use App\Models\AiRecommendation;
use App\Models\ProcurementRequest;
use Gemini\Data\Content;
use Gemini\Data\GenerationConfig;
use Gemini\Data\Schema;
use Gemini\Enums\DataType;
use Gemini\Enums\ResponseMimeType;
use Gemini\Laravel\Facades\Gemini;
use JsonException;
use RuntimeException;
use Throwable;

class AiEvaluationService
{
    /**
     * Prepare a procurement request for background AI evaluation.
     *
     * This creates a new AI recommendation record for a new request,
     * or resets the existing record when the same procurement request
     * needs to be evaluated again.
     *
     * AI lifecycle:
     *
     * Pending
     *   ↓
     * Processing
     *   ↓
     * Completed / Failed / Timeout
     */
    public function prepareForEvaluation(
        ProcurementRequest $procurementRequest
    ): AiRecommendation {

        return AiRecommendation::updateOrCreate(
            [
                'procurement_request_id' =>
                    $procurementRequest->procurement_request_id,
            ],
            [
                'recommended_quotation_id' => null,
                'justification_text' => null,
                'api_status' => 'Pending',
            ]
        );
    }


    /**
     * Run the complete AI evaluation lifecycle and save the result.
     *
     * IMPORTANT:
     * This method NEVER changes procurement_requests.request_status.
     *
     * The procurement request remains Pending_KJ while the AI
     * evaluation status is tracked separately.
     */
    public function evaluateAndSave(
        ProcurementRequest $procurementRequest
    ): AiRecommendation {

        /*
        |--------------------------------------------------------------------------
        | 1. Create or Reset AI Recommendation
        |--------------------------------------------------------------------------
        |
        | procurement_request_id is UNIQUE, therefore each procurement
        | request has only one current AI recommendation record.
        |
        | If the request is evaluated again, the same row is reused.
        |
        */

        $aiRecommendation = AiRecommendation::updateOrCreate(
            [
                'procurement_request_id' =>
                    $procurementRequest->procurement_request_id,
            ],
            [
                'recommended_quotation_id' => null,
                'justification_text' => null,
                'api_status' => 'Pending',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | 2. Mark Evaluation as Processing
        |--------------------------------------------------------------------------
        */

        $aiRecommendation->update([
            'api_status' => 'Processing',
        ]);


        try {

            /*
            |--------------------------------------------------------------------------
            | 3. Run Gemini Evaluation
            |--------------------------------------------------------------------------
            */

            $result = $this->evaluate(
                $procurementRequest
            );


            /*
            |--------------------------------------------------------------------------
            | 4. Save Successful Recommendation
            |--------------------------------------------------------------------------
            */

            $aiRecommendation->update([
                'recommended_quotation_id' =>
                    $result['recommended_quotation_id'],

                'justification_text' =>
                    $result['justification_text'],

                'api_status' =>
                    'Completed',
            ]);

        } catch (Throwable $exception) {
            
            // ===== ADDED START: log the real Gemini error =====
            \Illuminate\Support\Facades\Log::error(
                'Gemini Evaluation Failed: ' . $exception->getMessage()
            );
            // ===== ADDED END =====


            /*
            |--------------------------------------------------------------------------
            | Temporary Gemini Capacity Failure
            |--------------------------------------------------------------------------
            |
            | Gemini may occasionally report that the model is experiencing
            | high demand and ask the client to try again later.
            |
            | Because evaluation runs in Laravel's background queue, allow
            | the queue to retry these temporary failures automatically.
            |
            */

            if ($this->isTemporaryGeminiFailure($exception)) {

                $aiRecommendation->update([
                    'recommended_quotation_id' => null,
                    'justification_text' => null,
                    'api_status' => 'Pending',
                ]);

                report($exception);

                /*
                | Re-throw so Laravel's queue knows that this attempt failed
                | and can retry it using the configured backoff.
                */
                throw $exception;
            }


            /*
            |--------------------------------------------------------------------------
            | Normal Failure / Timeout
            |--------------------------------------------------------------------------
            */

            $aiRecommendation->update([
                'recommended_quotation_id' => null,
                'justification_text' => null,

                'api_status' =>
                    $this->isTimeoutException($exception)
                        ? 'Timeout'
                        : 'Failed',
            ]);

            report($exception);
        }


        /*
        |--------------------------------------------------------------------------
        | 6. Return Latest AI Recommendation
        |--------------------------------------------------------------------------
        */

        return $aiRecommendation->fresh();
    }


    /**
     * Evaluate all quotations belonging to one procurement request.
     *
     * This method:
     *
     * 1. Retrieves all quotations.
     * 2. Anonymizes vendors as Vendor A, Vendor B, Vendor C...
     * 3. Sends only approved quotation criteria to Gemini.
     * 4. Receives a structured recommendation.
     * 5. Validates the AI response.
     * 6. Maps the anonymous vendor back to the local quotation ID.
     */
    public function evaluate(
        ProcurementRequest $procurementRequest
    ): array {

        /*
        |--------------------------------------------------------------------------
        | 1. Retrieve Quotations
        |--------------------------------------------------------------------------
        |
        | Module 2 requires at least three distinct vendor quotations.
        | All quotations are evaluated, including 4 or more quotations.
        |
        */

        $quotations = $procurementRequest
            ->quotations()
            ->orderBy('quotation_id')
            ->get();


        if ($quotations->count() < 3) {

            throw new RuntimeException(
                'AI evaluation requires at least three vendor quotations.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Create Zero-PII Vendor Mapping
        |--------------------------------------------------------------------------
        |
        | Example:
        |
        | quotation_id 10 → Vendor A
        | quotation_id 11 → Vendor B
        | quotation_id 12 → Vendor C
        |
        | Gemini receives only Vendor A/B/C.
        |
        | Real quotation IDs stay inside UCPMS.
        |
        */

        $vendorMap = [];

        $anonymousQuotations = [];


        foreach ($quotations as $index => $quotation) {

            $vendorCode =
                'Vendor ' . $this->indexToLetters($index);


            /*
            | Local mapping.
            |
            | This is NEVER sent to Gemini.
            */
            $vendorMap[$vendorCode] =
                $quotation->quotation_id;


            /*
            |--------------------------------------------------------------------------
            | Zero-PII Gemini Payload
            |--------------------------------------------------------------------------
            |
            | Gemini receives only:
            |
            | - Anonymous vendor code
            | - Total quoted amount
            | - Warranty period
            | - Delivery lead time
            | - Payment terms
            |
            | Gemini does NOT receive:
            |
            | - Real vendor name
            | - vendor_id
            | - quotation_id
            | - Staff information
            | - PTJ information
            | - Budget information
            | - Quotation PDFs
            |
            */

            $anonymousQuotations[] = [

                'vendor_code' =>
                    $vendorCode,

                'total_quoted_amount_rm' =>
                    round(
                        (float) $quotation->total_amount,
                        2
                    ),

                'warranty_period_months' =>
                    (int) $quotation->warranty_period,

                'delivery_lead_time_days' =>
                    (int) $quotation->delivery_lead_time,

                'payment_terms_text' =>
                    trim(
                        (string) $quotation->payment_terms
                    ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Define Structured Gemini Response
        |--------------------------------------------------------------------------
        |
        | Expected response:
        |
        | {
        |     "recommended_vendor_code": "Vendor B",
        |     "justification": "..."
        | }
        |
        */

        $responseSchema = new Schema(
            type: DataType::OBJECT,

            properties: [

                'recommended_vendor_code' =>
                    new Schema(
                        type: DataType::STRING
                    ),

                'justification' =>
                    new Schema(
                        type: DataType::STRING
                    ),
            ],

            required: [
                'recommended_vendor_code',
                'justification',
            ],
        );


        /*
        |--------------------------------------------------------------------------
        | 4. Configure Structured JSON Output
        |--------------------------------------------------------------------------
        */

        $generationConfig = new GenerationConfig(
            responseMimeType:
                ResponseMimeType::APPLICATION_JSON,

            responseSchema:
                $responseSchema,
        );


        /*
        |--------------------------------------------------------------------------
        | 5. System Instruction
        |--------------------------------------------------------------------------
        |
        | UCPMS uses holistic Best Value evaluation.
        |
        | NO percentage weighting is invented because Phase 1 does not
        | define fixed weights.
        |
        */

        $systemInstruction = Content::parse(
            <<<'PROMPT'
You are the AI-assisted quotation evaluation component of a university procurement management system.

Your task is to evaluate anonymized vendor quotations and recommend the quotation that represents the strongest overall Best Value for Money.

Evaluate every quotation using ONLY the information supplied for these four criteria:

1. Total quoted amount
2. Warranty period
3. Delivery lead time
4. Payment terms, treated as literal text exactly as supplied

Evaluation rules:

- Consider all four criteria together.
- Do not apply invented percentage weights.
- Do not automatically recommend the cheapest quotation.
- Consider reasonable trade-offs between price, warranty, delivery lead time and payment terms.
- Use only the quotation information supplied in the request.
- Do not invent missing facts.
- Do not use external vendor information.
- Recommend exactly one of the supplied anonymous vendor codes.
- Treat payment_terms_text as literal text exactly as supplied.
- Never add a unit, expand, reinterpret, or infer a meaning that is not explicitly present in payment_terms_text.
- If the payment terms are identical across vendors, state only that they are identical and do not treat them as a differentiating factor.
- In the justification, compare the recommended quotation with the most relevant competing quotations and explain the important trade-offs using only the supplied values.
- The recommendation is advisory only.
- The Ketua Jabatan remains the final human decision-maker.
- Provide a concise and professional justification explaining why the selected quotation offers the strongest overall value compared with the alternatives.
PROMPT
        );


        /*
        |--------------------------------------------------------------------------
        | 6. Prepare Zero-PII Request Payload
        |--------------------------------------------------------------------------
        */

        $payload = [

            'quotation_count' =>
                count($anonymousQuotations),

            'quotations' =>
                $anonymousQuotations,
        ];


        $prompt =
            "Evaluate the following anonymized quotations "
            . "for Best Value for Money.\n\n"
            . "Quotation data:\n"
            . json_encode(
                $payload,
                JSON_PRETTY_PRINT
                | JSON_UNESCAPED_SLASHES
                | JSON_THROW_ON_ERROR
            );


        /*
        |--------------------------------------------------------------------------
        | 7. Call Gemini
        |--------------------------------------------------------------------------
        |
        | Model is read from:
        |
        | config/gemini.php
        |
        | Current .env:
        |
        | GEMINI_MODEL=gemini-3.6-flash
        |
        */

        $result = Gemini::generativeModel(
                model: config(
                    'gemini.model',
                    'gemini-3.6-flash'
                )
            )
            ->withSystemInstruction(
                $systemInstruction
            )
            ->withGenerationConfig(
                $generationConfig
            )
            ->generateContent(
                $prompt
            );


        /*
        |--------------------------------------------------------------------------
        | 8. Convert Gemini JSON into PHP Array
        |--------------------------------------------------------------------------
        */

        try {

            $response = json_decode(
                $result->text(),
                true,
                512,
                JSON_THROW_ON_ERROR
            );

        } catch (JsonException $exception) {

            throw new RuntimeException(
                'Gemini returned an invalid JSON response.',
                previous: $exception
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 9. Validate Required Response Fields
        |--------------------------------------------------------------------------
        */

        if (
            !is_array($response)
            ||
            !isset(
                $response['recommended_vendor_code'],
                $response['justification']
            )
        ) {

            throw new RuntimeException(
                'Gemini returned an incomplete quotation evaluation.'
            );
        }


        $recommendedVendorCode =
            trim(
                (string)
                $response['recommended_vendor_code']
            );


        $justification =
            trim(
                (string)
                $response['justification']
            );


        /*
        |--------------------------------------------------------------------------
        | 10. Validate Recommended Vendor
        |--------------------------------------------------------------------------
        |
        | Never trust AI output directly.
        |
        | Laravel confirms that the returned Vendor A/B/C code actually
        | belongs to this procurement request.
        |
        */

        if (
            !array_key_exists(
                $recommendedVendorCode,
                $vendorMap
            )
        ) {

            throw new RuntimeException(
                'Gemini recommended a vendor that does not belong to this procurement request.'
            );
        }


        if ($justification === '') {

            throw new RuntimeException(
                'Gemini returned an empty recommendation justification.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | 11. Return Validated Local UCPMS Result
        |--------------------------------------------------------------------------
        */

        return [

            'recommended_quotation_id' =>
                $vendorMap[
                    $recommendedVendorCode
                ],

            'recommended_vendor_code' =>
                $recommendedVendorCode,

            'justification_text' =>
                $justification,
        ];
    }


    /**
     * Convert numeric index into spreadsheet-style letters.
     *
     * 0  = A
     * 1  = B
     * 2  = C
     * ...
     * 25 = Z
     * 26 = AA
     *
     * This prevents Module 3 from being limited to only
     * three quotations.
     */
    private function indexToLetters(
        int $index
    ): string {

        $index++;

        $letters = '';


        while ($index > 0) {

            $index--;

            $letters =
                chr(
                    65 + ($index % 26)
                )
                . $letters;

            $index =
                intdiv(
                    $index,
                    26
                );
        }


        return $letters;
    }


    /**
     * Determine whether an exception appears to be caused
     * by an API/network timeout.
     */
    private function isTimeoutException(
        Throwable $exception
    ): bool {

        $message = strtolower(
            $exception->getMessage()
        );


        return
            str_contains(
                $message,
                'timed out'
            )
            ||
            str_contains(
                $message,
                'timeout'
            )
            ||
            str_contains(
                $message,
                'time-out'
            );
    }

        // ===== ADDED START: missing method (was called but never defined) =====
    /**
     * Detect temporary Gemini failures (rate limit / overload)
     * that are worth retrying through the queue.
     */
    private function isTemporaryGeminiFailure(
        Throwable $exception
    ): bool {

        $message = strtolower(
            $exception->getMessage()
        );

        foreach ([
            '429',
            '503',
            'rate limit',
            'resource_exhausted',
            'quota',
            'unavailable',
            'overloaded',
            'high demand',
            'try again later',
        ] as $keyword) {

            if (str_contains($message, $keyword)) {
                return true;
            }
        }

        return false;
    }
    // ===== ADDED END =====

}