<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // pending, confirmed, cancelled, attended
            $table->string('reference_code')->unique();
            $table->integer('ticket_quantity')->default(1);
            $table->decimal('amount_paid', 10, 2)->default(0.00);
            $table->string('payment_method')->nullable();
            $table->string('transaction_id')->nullable();
            $table->dateTime('payment_date')->nullable();
            $table->boolean('is_attended')->default(false);
            $table->dateTime('check_in_time')->nullable();
            $table->text('notes')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Each user can only register once for an event (unless they cancel and re-register)
            $table->unique(['user_id', 'event_id', 'deleted_at'], 'unique_registration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
