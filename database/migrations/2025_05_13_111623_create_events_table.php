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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Remove category_id foreign key for now
            $table->unsignedBigInteger('category_id')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('short_description')->nullable();
            $table->string('location');
            $table->string('address')->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();
            $table->integer('max_participants')->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->string('currency')->default('USD');
            $table->string('image_path')->nullable();
            $table->string('status')->default('draft'); // draft, published, cancelled, completed
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_private')->default(false);
            $table->string('access_code')->nullable();
            $table->json('custom_fields')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
