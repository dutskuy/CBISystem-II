<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
            $table->string('payment_code')->unique();   // PAY-2024-0001
            $table->decimal('amount', 15, 2);
            $table->string('bank_name');                // BCA, Mandiri, BNI, BRI
            $table->string('account_number');           // nomor rekening tujuan
            $table->string('account_name');             // nama pemilik rekening
            $table->string('transfer_proof')->nullable(); // bukti transfer (file)
            $table->string('sender_name')->nullable();
            $table->string('sender_bank')->nullable();
            $table->datetime('transfer_date')->nullable();
            $table->enum('status', [
                'pending',      // menunggu upload bukti
                'uploaded',     // bukti sudah diupload
                'verified',     // sudah diverifikasi admin
                'rejected'      // ditolak admin
            ])->default('pending');
            $table->text('rejection_reason')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users');
            $table->datetime('verified_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};