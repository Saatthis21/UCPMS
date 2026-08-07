<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ptj_budgets', function (Blueprint $table) {
            $table->id('budget_id');

            $table->unsignedBigInteger('ptj_id');

            $table->integer('budget_year');

            $table->decimal('allocated_amount', 15, 2);
            $table->decimal('used_amount', 15, 2)->default(0);
            $table->decimal('reserved_amount', 15, 2)->default(0);

            $table->timestamps();

            $table->foreign('ptj_id')
                ->references('ptj_id')
                ->on('ptj_departments');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ptj_budgets');
    }
};