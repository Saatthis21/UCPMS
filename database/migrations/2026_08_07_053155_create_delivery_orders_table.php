<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id('delivery_order_id');

            $table->unsignedBigInteger('local_order_id');
            $table->unsignedBigInteger('user_id');

            $table->string('do_number', 100);

            $table->string('do_pdf_path', 255);

            // Invoice is stored together with the delivery record
            $table->string('invoice_pdf_path', 255)->nullable();

            $table->enum('payment_status', [
                'Pending',
                'Paid'
            ])->default('Pending');

            $table->date('delivery_date');

            // Required by Manage Order Execution:
            // PTJ rates vendor after delivery
            $table->tinyInteger('vendor_rating');

            $table->text('delivery_comments')->nullable();

            $table->enum('delivery_order_status', [
                'Matched',
                'Incomplete',
                'Disputed'
            ]);

            $table->timestamps();

            $table->foreign('local_order_id')
                ->references('local_order_id')
                ->on('local_orders');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users');

            // One delivery record per Local Order
            $table->unique('local_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};