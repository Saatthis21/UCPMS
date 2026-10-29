<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->id('approval_id');

            $table->unsignedBigInteger('procurement_request_id');
            $table->unsignedBigInteger('user_id');

            $table->enum('approval_stage', [
                'KJ',
                'Bendahari'
            ]);

            $table->enum('decision', [
                'Approved',
                'Rejected',
                'Returned'
            ]);

            $table->text('remarks')->nullable();

            $table->timestamps();

            $table->foreign('procurement_request_id')
                ->references('procurement_request_id')
                ->on('procurement_requests');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('approvals');
    }
};