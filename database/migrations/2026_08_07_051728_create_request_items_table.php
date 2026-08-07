<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->id('request_item_id');

            $table->unsignedBigInteger('procurement_request_id');

            $table->string('item_name', 100);

            $table->text('item_description')->nullable();

            $table->integer('quantity');

            $table->decimal('unit_price', 15, 2);

            $table->decimal('total_price', 15, 2);

            $table->timestamps();

            $table->foreign('procurement_request_id')
                ->references('procurement_request_id')
                ->on('procurement_requests');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('request_items');
    }
};