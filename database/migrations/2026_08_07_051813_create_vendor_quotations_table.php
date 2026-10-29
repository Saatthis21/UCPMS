<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendor_quotations', function (Blueprint $table) {
            $table->id('quotation_id');

            $table->unsignedBigInteger('procurement_request_id');
            $table->unsignedBigInteger('vendor_id');

            // Added based on updated UCPMS requirement
            $table->string('quotation_reference_number', 100);
            $table->date('quotation_date');

            $table->decimal('total_amount', 15, 2);

            // Each vendor quotation has its own PDF
            $table->string('quotation_file_path', 255);

            $table->integer('warranty_period')->nullable();
            $table->integer('delivery_lead_time')->nullable();
            $table->string('payment_terms', 255)->nullable();

            $table->timestamps();

            $table->foreign('procurement_request_id')
                ->references('procurement_request_id')
                ->on('procurement_requests');

            $table->foreign('vendor_id')
                ->references('vendor_id')
                ->on('vendors');

            // Prevent same vendor being selected twice
            // for the same procurement request
            $table->unique([
                'procurement_request_id',
                'vendor_id'
            ]);
        });

        /*
        |--------------------------------------------------------------------------
        | Selected quotation FK
        |--------------------------------------------------------------------------
        | procurement_requests is created before vendor_quotations.
        | Therefore we add this FK only AFTER vendor_quotations exists.
        */
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->foreign('selected_quotation_id')
                ->references('quotation_id')
                ->on('vendor_quotations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        /*
        | Remove this FK first because procurement_requests
        | depends on vendor_quotations.
        */
        Schema::table('procurement_requests', function (Blueprint $table) {
            $table->dropForeign(['selected_quotation_id']);
        });

        Schema::dropIfExists('vendor_quotations');
    }
};