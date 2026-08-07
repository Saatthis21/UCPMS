<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ptj_departments', function (Blueprint $table) {
            $table->id('ptj_id');

            $table->string('department_name', 100);
            $table->string('department_code', 50)->unique();
            $table->boolean('is_active')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ptj_departments');
    }
};