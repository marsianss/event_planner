<?php

namespace Database\Seeders;

use App\Models\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tags = [
            ['name' => 'Free', 'color' => '#10B981'],
            ['name' => 'Premium', 'color' => '#8B5CF6'],
            ['name' => 'Family-friendly', 'color' => '#3B82F6'],
            ['name' => 'Adults-only', 'color' => '#EC4899'],
            ['name' => 'Outdoors', 'color' => '#22C55E'],
            ['name' => 'Indoor', 'color' => '#F97316'],
            ['name' => 'Beginner', 'color' => '#14B8A6'],
            ['name' => 'Advanced', 'color' => '#EF4444'],
            ['name' => 'Hands-on', 'color' => '#F59E0B'],
            ['name' => 'Presentation', 'color' => '#6366F1'],
            ['name' => 'Weekend', 'color' => '#D946EF'],
            ['name' => 'Weekday', 'color' => '#6B7280'],
            ['name' => 'Evening', 'color' => '#1E40AF'],
            ['name' => 'Daytime', 'color' => '#0EA5E9'],
            ['name' => 'Multi-day', 'color' => '#F43F5E'],
            ['name' => 'International', 'color' => '#0D9488'],
            ['name' => 'Local', 'color' => '#84CC16'],
            ['name' => 'Featured', 'color' => '#EAB308'],
            ['name' => 'New', 'color' => '#0284C7'],
            ['name' => 'Last-minute', 'color' => '#DC2626'],
            ['name' => 'Limited-seats', 'color' => '#9333EA'],
            ['name' => 'Accessible', 'color' => '#2563EB'],
            ['name' => 'Catered', 'color' => '#EA580C'],
        ];

        foreach ($tags as $tag) {
            Tag::create([
                'name' => $tag['name'],
                'slug' => Str::slug($tag['name']),
                'color' => $tag['color'],
            ]);
        }
    }
}
