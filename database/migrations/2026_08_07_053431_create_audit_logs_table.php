<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id('audit_log_id');

            $table->unsignedBigInteger('procurement_request_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('action', 100);

            $table->text('description')->nullable();

            $table->timestamp('created_at')->useCurrent();

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
        Schema::dropIfExists('audit_logs');
    }
};