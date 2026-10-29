<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_recommendations', function (Blueprint $table) {
            $table->id('ai_recommendation_id');

            $table->unsignedBigInteger('procurement_request_id')->unique();

            $table->unsignedBigInteger('recommended_quotation_id')->nullable();

            $table->text('justification_text')->nullable();

            $table->enum('api_status', [
                'Pending',
                'Processing',
                'Completed',
                'Failed',
                'Timeout'
            ])->default('Pending');

            $table->timestamps();

            $table->foreign('procurement_request_id')
                ->references('procurement_request_id')
                ->on('procurement_requests');

            $table->foreign('recommended_quotation_id')
                ->references('quotation_id')
                ->on('vendor_quotations')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_recommendations');
    }
};