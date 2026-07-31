<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('vendor_id')->constrained('vendors')->cascadeOnDelete();
            $table->foreignId('expo_id')->nullable()->constrained('expos')->nullOnDelete();

            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->unsignedInteger('duration_minutes')->nullable();

            $table->enum('location_type', ['in_person', 'online']);
            $table->string('location_detail')->nullable();

            $table->string('subject');
            $table->text('notes')->nullable();
            $table->unsignedInteger('attendee_count')->nullable();

            $table->enum('preferred_contact', ['whatsapp', 'phone', 'email'])->nullable();
            $table->string('contact_number')->nullable();

            $table->enum('status', ['requested', 'confirmed', 'rescheduled', 'cancelled', 'completed'])->default('requested');

            $table->timestamps();
            $table->softDeletes();

            $table->index(['vendor_id', 'starts_at']);
            $table->index(['customer_id', 'starts_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};