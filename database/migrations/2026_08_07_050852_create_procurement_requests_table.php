<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('procurement_requests', function (Blueprint $table) {
            $table->id('procurement_request_id');

            $table->unsignedBigInteger('ptj_id');
            $table->unsignedBigInteger('user_id');

            $table->string('request_title', 100);
            $table->text('request_description');

            // Automatically calculated later from request_items
            $table->decimal('estimated_amount', 15, 2);

            // Filled only after KJ selects the winning quotation
            $table->unsignedBigInteger('selected_quotation_id')->nullable();

            // Locked amount from the quotation selected by KJ
            $table->decimal('approved_amount', 15, 2)->nullable();

            $table->enum('request_status', [
                'Draft',
                'Pending_KJ',
                'Pending_Edit',
                'Approved_KJ',
                'Rejected_KJ',
                'Approved_Bendahari',
                'Rejected_Bendahari',
                'LO_Generated',
                'Delivery_Received',
                'Completed'
            ])->default('Draft');

            $table->timestamps();

            $table->foreign('ptj_id')
                ->references('ptj_id')
                ->on('ptj_departments');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('procurement_requests');
    }
};