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

            $table->date('delivery_date');

            $table->tinyInteger('vendor_rating');

            $table->text('delivery_comments')->nullable();

            $table->timestamps();

            $table->foreign('local_order_id')
                ->references('local_order_id')
                ->on('local_orders');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users');

            $table->unique('local_order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};