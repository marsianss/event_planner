<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Tag;
use App\Models\User;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all organizers
        $organizers = User::where('role', 'organizer')->orWhere('role', 'admin')->get();
        $categories = Category::all();
        $tags = Tag::all();

        // Create events for each organizer
        foreach ($organizers as $organizer) {
            // Create 3-7 events per organizer
            $numEvents = rand(3, 7);

            for ($i = 0; $i < $numEvents; $i++) {
                // Select random category
                $category = $categories->random();

                // Determine if event is in the past, present, or future
                $timeframe = rand(0, 10);
                if ($timeframe < 2) {
                    // Past event (20% chance)
                    $startDate = Carbon::now()->subDays(rand(5, 60));
                    $status = rand(0, 1) ? 'completed' : 'cancelled';
                } elseif ($timeframe < 4) {
                    // Current/ongoing event (20% chance)
                    $startDate = Carbon::now()->subDays(rand(1, 3));
                    $status = 'published';
                } else {
                    // Future event (60% chance)
                    $startDate = Carbon::now()->addDays(rand(3, 180));
                    $status = rand(0, 10) < 8 ? 'published' : 'draft';
                }

                // End date logic
                $endDate = null;
                $isMultiDay = rand(0, 10) > 7; // 30% chance of multi-day event
                if ($isMultiDay) {
                    $endDate = (clone $startDate)->addDays(rand(1, 5));
                }

                // Price logic
                $isFree = rand(0, 10) < 3; // 30% chance of free event
                $price = $isFree ? 0 : rand(10, 500);

                // Create the event
                $eventTitle = $this->generateEventTitle($category->name);
                $event = Event::create([
                    'user_id' => $organizer->id,
                    'category_id' => $category->id,
                    'title' => $eventTitle,
                    'description' => $this->generateEventDescription($category->name),
                    'short_description' => Str::limit($this->generateEventDescription($category->name, true), 150),
                    'location' => $this->getRandomLocation(),
                    'address' => $this->getRandomAddress(),
                    'latitude' => rand(50, 54) + (rand(0, 1000000) / 1000000),
                    'longitude' => rand(3, 7) + (rand(0, 1000000) / 1000000),
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'max_participants' => rand(0, 10) < 8 ? rand(10, 500) : null,
                    'price' => $price,
                    'currency' => 'EUR',
                    'status' => $status,
                    'is_featured' => rand(0, 10) < 2, // 20% chance of featured
                    'is_private' => rand(0, 10) < 1, // 10% chance of private
                    'access_code' => rand(0, 10) < 1 ? Str::random(8) : null,
                ]);

                // Attach 2-5 random tags to each event
                $selectedTags = $tags->random(rand(2, 5));
                foreach ($selectedTags as $tag) {
                    DB::table('event_tag')->insert([
                        'event_id' => $event->id,
                        'tag_id' => $tag->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

        // Create some additional featured events
        for ($i = 0; $i < 5; $i++) {
            $category = $categories->random();
            $organizer = $organizers->random();
            $startDate = Carbon::now()->addDays(rand(7, 30));

            $eventTitle = $this->generateEventTitle($category->name, true);
            $event = Event::create([
                'user_id' => $organizer->id,
                'category_id' => $category->id,
                'title' => $eventTitle,
                'description' => $this->generateEventDescription($category->name, true),
                'short_description' => "Featured: " . Str::limit($this->generateEventDescription($category->name, true), 100),
                'location' => $this->getRandomLocation(),
                'address' => $this->getRandomAddress(),
                'latitude' => rand(50, 54) + (rand(0, 1000000) / 1000000),
                'longitude' => rand(3, 7) + (rand(0, 1000000) / 1000000),
                'start_date' => $startDate,
                'end_date' => (clone $startDate)->addDays(rand(1, 3)),
                'max_participants' => rand(50, 1000),
                'price' => rand(0, 10) < 3 ? 0 : rand(25, 300),
                'currency' => 'EUR',
                'status' => 'published',
                'is_featured' => true,
                'is_private' => false,
            ]);

            // Attach the "Featured" tag and 2-4 other random tags
            $featuredTag = Tag::where('name', 'Featured')->first();
            $otherTags = $tags->where('id', '!=', $featuredTag->id)->random(rand(2, 4));

            DB::table('event_tag')->insert([
                'event_id' => $event->id,
                'tag_id' => $featuredTag->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            foreach ($otherTags as $tag) {
                DB::table('event_tag')->insert([
                    'event_id' => $event->id,
                    'tag_id' => $tag->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Generate a realistic event title based on category
     */
    private function generateEventTitle($category, $isFeatured = false)
    {
        $adjectives = ['Annual', 'International', 'Premier', 'Ultimate', 'Professional', 'Advanced', 'Beginner', 'Master',
                       'Expert', 'Exclusive', 'Interactive', 'Immersive', 'Virtual', 'Live', 'Dutch', 'European'];

        $prefix = $isFeatured ? $adjectives[array_rand($adjectives)] . ' ' : '';

        $titles = [
            'Conferences' => [
                'Tech Innovation Summit', 'Business Leadership Conference', 'Digital Transformation Forum',
                'Future of Work Conference', 'Sustainability Summit', 'Industry Leaders Meetup',
                'Global Technology Conference', 'Marketing Strategy Summit', 'Healthcare Innovation Conference',
                'AI & Machine Learning Symposium', 'Blockchain Revolution Conference', 'DevOps Days'
            ],
            'Workshops' => [
                'Data Science Bootcamp', 'UX Design Workshop', 'Creative Writing Masterclass',
                'Public Speaking Intensive', 'Leadership Skills Workshop', 'Photography Basics',
                'Financial Planning Workshop', 'Mobile App Development Crash Course', 'Sustainable Living Workshop',
                'Career Development Seminar', 'Cooking Masterclass', 'Pottery Workshop'
            ],
            'Networking' => [
                'Young Professionals Mixer', 'Business Breakfast Club', 'Industry Networking Night',
                'Entrepreneur Meet and Greet', 'Women in Business Networking', 'Tech Professionals Happy Hour',
                'Creative Industries Networking', 'Startup Community Gathering', 'Finance Professionals Connect',
                'Healthcare Networking Event', 'Executive Mixer', 'Speed Networking Session'
            ],
            'Concerts' => [
                'Summer Music Festival', 'Jazz Night Live', 'Rock in the Park',
                'Classical Symphony Orchestra', 'Electronic Dance Night', 'Acoustic Sessions',
                'Hip-Hop Showcase', 'Indie Music Spotlight', 'Country Music Festival',
                'Opera Night', 'Battle of the Bands', 'Music Under the Stars'
            ],
            'Festivals' => [
                'Cultural Heritage Festival', 'Food & Wine Celebration', 'Film Festival',
                'Arts & Crafts Fair', 'Literature Festival', 'Comedy Festival',
                'Street Food Festival', 'Craft Beer Festival', 'International Film Festival',
                'Poetry Festival', 'Theatre Festival', 'Dance Festival'
            ],
            'Sports' => [
                'Marathon Challenge', 'Football Tournament', 'Tennis Open',
                'Cycling Race', 'Basketball Championship', 'Yoga in the Park',
                'Swimming Competition', 'Fitness Challenge', 'Golf Tournament',
                'Esports Championship', 'Chess Tournament', 'Martial Arts Competition'
            ],
            'Exhibitions' => [
                'Modern Art Exhibition', 'Photography Showcase', 'Historical Artifacts Display',
                'Science & Technology Expo', 'Architecture Design Exhibition', 'Fashion Showcase',
                'Automotive Show', 'Home & Garden Expo', 'Product Innovation Exhibition',
                'Craft Show', 'Trade Fair', 'Design Exhibition'
            ],
            'Food & Drink' => [
                'Wine Tasting Event', 'Culinary Festival', 'Baking Competition',
                'Cheese & Wine Pairing', 'Cooking Competition', 'Craft Beer Tasting',
                'Food Truck Festival', 'Vegan Food Fair', 'Coffee Brewing Workshop',
                'International Food Festival', 'BBQ Cook-off', 'Dessert Fair'
            ],
            'Tech' => [
                'Hackathon Challenge', 'Startup Pitch Competition', 'VR/AR Demo Day',
                'Coding Bootcamp', 'Tech Product Launch', 'Robotics Competition',
                'Web Development Meetup', 'Mobile Gaming Tournament', 'IoT Solutions Showcase',
                'Cybersecurity Conference', 'Data Science Meetup', 'Tech Career Fair'
            ],
            'Charity' => [
                'Fundraising Gala', 'Charity Run for Cause', 'Community Volunteer Day',
                'Benefit Concert', 'Auction for Charity', 'Food Drive',
                'Environmental Cleanup Day', 'Charity Sports Tournament', 'Children\'s Charity Event',
                'Animal Shelter Fundraiser', 'Humanitarian Aid Fundraiser', 'Community Support Initiative'
            ],
            'Education' => [
                'Science Fair', 'Academic Lecture Series', 'College & University Fair',
                'Study Abroad Seminar', 'Language Learning Workshop', 'Education Technology Summit',
                'STEM Education Day', 'Teacher Training Workshop', 'Professional Certification Course',
                'Youth Leadership Program', 'Continuing Education Fair', 'Research Symposium'
            ],
            'Virtual' => [
                'Online Learning Conference', 'Virtual Reality Experience', 'Webinar Series',
                'Digital Art Showcase', 'Online Gaming Tournament', 'Virtual Career Fair',
                'Remote Work Summit', 'Digital Marketing Webinar', 'Virtual Fitness Challenge',
                'Online Networking Event', 'Virtual Book Club', 'Digital Conference'
            ],
        ];

        // If category doesn't have specific titles, use a mix of all titles
        if (!isset($titles[$category])) {
            $allTitles = [];
            foreach ($titles as $categoryTitles) {
                $allTitles = array_merge($allTitles, $categoryTitles);
            }
            return $prefix . $allTitles[array_rand($allTitles)];
        }

        $categoryTitles = $titles[$category];
        return $prefix . $categoryTitles[array_rand($categoryTitles)];
    }

    /**
     * Generate a realistic event description
     */
    private function generateEventDescription($category, $isDetailed = false)
    {
        $descriptions = [
            'Conferences' => 'Join industry leaders and experts for an insightful discussion on the latest trends and innovations. Network with professionals, gain valuable knowledge, and discover new opportunities in your field.',
            'Workshops' => 'Develop your skills in this hands-on workshop led by experienced professionals. Learn practical techniques, receive personalized feedback, and take your abilities to the next level.',
            'Networking' => 'Expand your professional network at this exclusive gathering of industry leaders and professionals. Build valuable connections, exchange ideas, and discover new collaboration opportunities.',
            'Concerts' => 'Experience an unforgettable night of live music with stellar performances that will leave you wanting more. Feel the energy of the crowd and enjoy a world-class sound and lighting experience.',
            'Festivals' => 'Immerse yourself in a celebration of culture, creativity, and community. Enjoy a diverse program of activities, performances, and experiences for all ages.',
            'Sports' => 'Witness the thrill of competition as top athletes showcase their skills and determination. Experience the excitement, camaraderie, and sportsmanship of this premier sporting event.',
            'Exhibitions' => 'Explore a carefully curated collection of works that push boundaries and inspire new perspectives. Engage with the creators and gain insight into their creative process and vision.',
            'Food & Drink' => 'Indulge your palate with exceptional flavors and culinary creations from renowned chefs and producers. Discover new tastes, learn about food preparation, and enjoy a gastronomic adventure.',
            'Tech' => 'Stay ahead of the curve with cutting-edge technologies and innovative solutions. Connect with tech enthusiasts, learn from industry experts, and explore the future of technology.',
            'Charity' => 'Make a difference while enjoying a memorable experience. Your participation directly supports important causes and helps create positive change in our community.',
            'Education' => 'Expand your knowledge and gain valuable insights from leading experts in the field. Enhance your understanding through interactive learning experiences and thought-provoking discussions.',
            'Virtual' => 'Participate from anywhere in the world in this innovative online event. Engage with content, connect with other participants, and enjoy a seamless digital experience.',
        ];

        // If category doesn't have a specific description, use a general one
        $baseDescription = isset($descriptions[$category])
            ? $descriptions[$category]
            : 'Join us for this exciting event where you can connect with like-minded individuals, gain valuable experiences, and create lasting memories.';

        if (!$isDetailed) {
            return $baseDescription;
        }

        // Additional paragraphs for detailed descriptions
        $additionalContent = [
            "## What to Expect\n\nThis event offers a unique opportunity to [specific benefit related to category]. Whether you're a seasoned professional or just getting started, you'll find valuable content and connections that will help you grow.\n\n",
            "## Who Should Attend\n\nThis event is perfect for individuals who are interested in [category-specific audience]. If you're passionate about [topic related to category], this is an event you won't want to miss.\n\n",
            "## Highlights\n\n- Special presentations from industry leaders\n- Networking opportunities with peers and experts\n- Interactive sessions and hands-on activities\n- Exclusive content and resources\n- Refreshments and amenities included\n\n",
            "## Location Details\n\nThe venue is conveniently located with ample parking and accessibility features. Public transportation options are available nearby, making it easy for all attendees to join us.\n\n"
        ];

        // Combine base description with 2-3 additional paragraphs
        $numParagraphs = rand(2, 3);
        $selectedParagraphs = array_rand($additionalContent, $numParagraphs);
        if (!is_array($selectedParagraphs)) {
            $selectedParagraphs = [$selectedParagraphs];
        }

        $fullDescription = $baseDescription . "\n\n";
        foreach ($selectedParagraphs as $index) {
            $fullDescription .= $additionalContent[$index];
        }

        return $fullDescription;
    }

    /**
     * Get a random location in the Netherlands
     */
    private function getRandomLocation()
    {
        $cities = [
            'Amsterdam', 'Rotterdam', 'The Hague', 'Utrecht', 'Eindhoven',
            'Groningen', 'Tilburg', 'Almere', 'Breda', 'Nijmegen',
            'Haarlem', 'Arnhem', 'Zaanstad', 'Amersfoort', 'Apeldoorn',
            'Den Bosch', 'Maastricht', 'Leiden', 'Dordrecht', 'Enschede'
        ];

        return $cities[array_rand($cities)];
    }

    /**
     * Get a random address
     */
    private function getRandomAddress()
    {
        $streets = [
            'Hoofdstraat', 'Kerkstraat', 'Dorpsstraat', 'Schoolstraat', 'Molenweg',
            'Julianastraat', 'Stationsweg', 'Beatrixstraat', 'Wilhelminastraat', 'Prinsenstraat',
            'Oranjelaan', 'Marktplein', 'Parkweg', 'Industrieweg', 'Bergweg'
        ];

        $venueTypes = [
            'Conference Center', 'Exhibition Hall', 'Theater', 'Stadium', 'Arena',
            'Cultural Center', 'Community Hall', 'Hotel', 'University', 'Museum',
            'Gallery', 'Auditorium', 'Sports Complex', 'Park', 'Convention Center'
        ];

        $useVenue = rand(0, 1);

        if ($useVenue) {
            $venue = $venueTypes[array_rand($venueTypes)];
            return "$venue, " . $streets[array_rand($streets)] . " " . rand(1, 150);
        } else {
            return $streets[array_rand($streets)] . " " . rand(1, 150);
        }
    }
}
