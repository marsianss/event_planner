<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'username' => 'admin',
            'phone' => '1234567890',
            'bio' => 'System administrator with full access to all features.',
            'location' => 'Amsterdam, Netherlands',
            'role' => 'admin',
            'is_verified_organizer' => true,
            'last_active_at' => now(),
            'profile_picture' => 'https://example.com/images/admin.jpg',
            'x' => 'https://x.com/admin',
            'linkedin' => 'https://linkedin.com/in/admin',
            'instagram' => 'https://instagram.com/admin',
        ]);

        // Create organizer users
        User::create([
            'name' => 'Event Organizer',
            'email' => 'organizer@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'username' => 'eventpro',
            'phone' => '9876543210',
            'bio' => 'Professional event organizer with 10+ years of experience.',
            'location' => 'Rotterdam, Netherlands',
            'website' => 'https://eventpro.example.com',
            'company' => 'EventPro Solutions',
            'job_title' => 'Senior Event Coordinator',
            'role' => 'organizer',
            'is_verified_organizer' => true,
            'last_active_at' => now(),
            'profile_picture' => 'https://example.com/images/organizer.jpg',
            'x' => 'https://x.com/eventpro',
            'linkedin' => 'https://linkedin.com/in/eventpro',
            'instagram' => 'https://instagram.com/eventpro',
        ]);

        // Create another organizer with different specialization
        User::create([
            'name' => 'Concert Organizer',
            'email' => 'concerts@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'username' => 'concertmaster',
            'phone' => '5551234567',
            'bio' => 'Specialized in organizing music events and concerts.',
            'location' => 'Utrecht, Netherlands',
            'company' => 'MusicWave Productions',
            'job_title' => 'Music Event Director',
            'role' => 'organizer',
            'is_verified_organizer' => true,
            'last_active_at' => now(),
            'profile_picture' => 'https://example.com/images/concertmaster.jpg',
            'x' => 'https://x.com/concertmaster',
            'linkedin' => 'https://linkedin.com/in/concertmaster',
            'instagram' => 'https://instagram.com/concertmaster',
        ]);

        // Create regular users
        User::factory(25)->create();

        // Create additional users with organizer role
        User::factory(5)->create([
            'role' => 'organizer',
            'is_verified_organizer' => true,
        ]);
    }
}
