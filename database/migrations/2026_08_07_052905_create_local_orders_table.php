<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('local_orders', function (Blueprint $table) {
            $table->id('local_order_id');

            $table->unsignedBigInteger('procurement_request_id');

            $table->unsignedBigInteger('selected_quotation_id');

            $table->string('lo_number', 50)->unique();

            $table->date('lo_date');

            $table->decimal('total_amount', 15, 2);

            $table->string('lo_file_path', 255)->nullable();

            $table->enum('lo_status', [
                'Draft',
                'Generated',
                'Delivered',
                'Closed'
            ])->default('Draft');

            $table->timestamps();

            $table->foreign('procurement_request_id')
                ->references('procurement_request_id')
                ->on('procurement_requests');

            $table->foreign('selected_quotation_id')
                ->references('quotation_id')
                ->on('vendor_quotations');

            // One Local Order per procurement request
            $table->unique('procurement_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('local_orders');
    }
};