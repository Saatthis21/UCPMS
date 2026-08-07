<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id('invoice_id');

            $table->unsignedBigInteger('local_order_id');
            $table->unsignedBigInteger('user_id');

            $table->string('invoice_number', 100);

            $table->date('invoice_date');

            $table->decimal('invoice_amount', 15, 2);

            $table->string('invoice_file_path', 255);

            $table->enum('payment_status', [
                'Pending',
                'Paid'
            ])->default('Pending');

            $table->timestamp('paid_at')->nullable();

            $table->unsignedBigInteger('marked_paid_by')->nullable();

            $table->timestamps();

            $table->foreign('local_order_id')
                ->references('local_order_id')
                ->on('local_orders');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users');

            $table->foreign('marked_paid_by')
                ->references('user_id')
                ->on('users');

            $table->unique('local_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};