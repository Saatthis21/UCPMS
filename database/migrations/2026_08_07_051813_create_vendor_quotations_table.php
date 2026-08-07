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

            $table->decimal('total_amount', 15, 2);

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

            // Prevent the same vendor being added twice
            // to the same procurement request.
            $table->unique([
                'procurement_request_id',
                'vendor_id'
            ]);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendor_quotations');
    }
};