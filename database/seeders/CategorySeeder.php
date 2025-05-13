<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Conferences',
                'description' => 'Professional gatherings focused on specific industries or topics',
                'color' => '#4F46E5',
                'icon' => 'fa-solid fa-microphone-lines',
                'display_order' => 1,
            ],
            [
                'name' => 'Workshops',
                'description' => 'Hands-on interactive sessions for skill development',
                'color' => '#16A34A',
                'icon' => 'fa-solid fa-chalkboard-user',
                'display_order' => 2,
            ],
            [
                'name' => 'Networking',
                'description' => 'Events focused on building professional connections',
                'color' => '#2563EB',
                'icon' => 'fa-solid fa-handshake',
                'display_order' => 3,
            ],
            [
                'name' => 'Concerts',
                'description' => 'Live music performances across all genres',
                'color' => '#DB2777',
                'icon' => 'fa-solid fa-music',
                'display_order' => 4,
            ],
            [
                'name' => 'Festivals',
                'description' => 'Celebrations of culture, music, or arts spanning multiple days',
                'color' => '#EA580C',
                'icon' => 'fa-solid fa-masks-theater',
                'display_order' => 5,
            ],
            [
                'name' => 'Sports',
                'description' => 'Athletic competitions and sporting events',
                'color' => '#65A30D',
                'icon' => 'fa-solid fa-trophy',
                'display_order' => 6,
            ],
            [
                'name' => 'Exhibitions',
                'description' => 'Art, cultural, or industry showcase events',
                'color' => '#7C3AED',
                'icon' => 'fa-solid fa-palette',
                'display_order' => 7,
            ],
            [
                'name' => 'Food & Drink',
                'description' => 'Culinary experiences including tastings, food festivals, and cooking classes',
                'color' => '#B45309',
                'icon' => 'fa-solid fa-utensils',
                'display_order' => 8,
            ],
            [
                'name' => 'Tech',
                'description' => 'Technology-focused events including product launches and hackathons',
                'color' => '#0891B2',
                'icon' => 'fa-solid fa-microchip',
                'display_order' => 9,
            ],
            [
                'name' => 'Charity',
                'description' => 'Fundraising and awareness events for causes and non-profits',
                'color' => '#BE185D',
                'icon' => 'fa-solid fa-heart',
                'display_order' => 10,
            ],
            [
                'name' => 'Education',
                'description' => 'Learning-focused events including lectures and seminars',
                'color' => '#0369A1',
                'icon' => 'fa-solid fa-graduation-cap',
                'display_order' => 11,
            ],
            [
                'name' => 'Virtual',
                'description' => 'Online events including webinars and virtual conferences',
                'color' => '#6D28D9',
                'icon' => 'fa-solid fa-globe',
                'display_order' => 12,
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'color' => $category['color'],
                'icon' => $category['icon'],
                'display_order' => $category['display_order'],
                'is_active' => true,
            ]);
        }
    }
}
