<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id('vendor_id');

            $table->string('company_name', 100);

            $table->string('ssm_number', 50)->unique();

            $table->string('mof_number', 50)->nullable();

            $table->string('bank_account', 100);

            $table->decimal('vendor_rating', 3, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};