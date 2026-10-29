<?php

namespace App\Jobs;

use App\Models\AiRecommendation;
use App\Models\ProcurementRequest;
use App\Services\AiEvaluationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Throwable;

class EvaluateQuotationJob implements ShouldQueue
{
    use Queueable;

    /**
     * Only attempt this queued job once.
     *
     * Gemini/API failures are already handled by
     * AiEvaluationService as Failed or Timeout.
     */
    public int $tries = 3;


    /**
     * Wait before retrying a temporary Gemini failure.
     *
     * Attempt 1 fails → wait 15 seconds
     * Attempt 2 fails → wait 30 seconds
     */
    public function backoff(): array
    {
        return [15, 30];
    }

    /**
     * Maximum execution time for this queue job.
     *
     * Gemini request timeout = 60 seconds
     * Queue job timeout      = 75 seconds
     * Database retry_after   = 90 seconds
     */
    public int $timeout = 75;

    /**
     * If Laravel itself times out this job,
     * consider the queue job failed.
     */
    public bool $failOnTimeout = true;


    /**
     * Create the job.
     */
    public function __construct(
        public int $procurementRequestId
    ) {
    }


    /**
     * Execute the background AI quotation evaluation.
     */
    public function handle(
        AiEvaluationService $aiEvaluationService
    ): void {

        /*
        |--------------------------------------------------------------------------
        | 1. Retrieve Procurement Request
        |--------------------------------------------------------------------------
        */

        $procurementRequest = ProcurementRequest::find(
            $this->procurementRequestId
        );


        /*
        | If the request no longer exists, there is nothing
        | for the worker to evaluate.
        */
        if (!$procurementRequest) {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 2. Workflow Safety Check
        |--------------------------------------------------------------------------
        |
        | AI evaluation belongs to the Pending_KJ stage.
        |
        | If the request has already moved to another workflow state,
        | do not run an old/stale AI evaluation.
        |
        */

        if ($procurementRequest->request_status !== 'Pending_KJ') {
            return;
        }


        /*
        |--------------------------------------------------------------------------
        | 3. Run AI Evaluation
        |--------------------------------------------------------------------------
        |
        | evaluateAndSave() handles:
        |
        | Pending
        |    ↓
        | Processing
        |    ↓
        | Completed / Failed / Timeout
        |
        */

        $aiEvaluationService->evaluateAndSave(
            $procurementRequest
        );
    }


    /**
     * Handle an unexpected queue-level failure.
     *
     * Normal Gemini errors/timeouts are handled inside
     * AiEvaluationService. This method covers failures occurring
     * at the Laravel queue/job level itself.
     */
    public function failed(
        ?Throwable $exception
    ): void {

        AiRecommendation::where(
            'procurement_request_id',
            $this->procurementRequestId
        )
            ->whereIn(
                'api_status',
                [
                    'Pending',
                    'Processing',
                ]
            )
            ->update([
                'recommended_quotation_id' => null,
                'justification_text' => null,
                'api_status' => 'Failed',
            ]);


        if ($exception) {
            report($exception);
        }
    }
}