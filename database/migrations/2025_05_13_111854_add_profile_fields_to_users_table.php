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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique();
            $table->string('phone')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('bio')->nullable();
            $table->string('profile_image')->nullable();
            $table->string('location')->nullable();
            $table->string('website')->nullable();
            $table->string('company')->nullable();
            $table->string('job_title')->nullable();
            $table->json('preferences')->nullable();
            $table->enum('role', ['user', 'organizer', 'admin'])->default('user');
            $table->boolean('is_verified_organizer')->default(false);
            $table->timestamp('last_active_at')->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username', 'phone', 'birth_date', 'bio', 'profile_image',
                'location', 'website', 'company', 'job_title', 'preferences',
                'role', 'is_verified_organizer', 'last_active_at', 'deleted_at'
            ]);
        });
    }
};
