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
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->string('provider')->default('midtrans');
            $table->string('external_id')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending');
            $table->decimal('amount', 12, 2)->default(0);
            $table->string('method')->nullable();
            $table->string('va_number')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->json('raw_response')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};