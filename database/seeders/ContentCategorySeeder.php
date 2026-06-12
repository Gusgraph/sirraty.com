<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/seeders/ContentCategorySeeder.php
// =====================================================

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ContentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $sets = [
            'pages' => ['News & Media', 'Local News', 'World News', 'Community Updates', 'Politics & Public Affairs', 'Business News', 'Finance Education', 'Stock Market Education', 'Halal Investing', 'Real Estate Education', 'Entrepreneurship', 'Small Business', 'Digital Marketing', 'E-commerce', 'Affiliate Marketing', 'Freelancing', 'Career Advice', 'Jobs & Hiring', 'Education', 'Online Courses', 'Language Learning', 'Islamic Education', 'Qur’an Reflections', 'Hadith Benefits', 'Islamic History', 'Muslim Family Life', 'Parenting', 'Marriage Advice', 'Youth Advice', 'Health & Wellness', 'Fitness', 'Nutrition', 'Mental Wellness', 'Natural Remedies', 'Sports', 'Football', 'Basketball', 'Combat Sports', 'Cars & Motors', 'Trucking', 'Aviation', 'Travel', 'Food', 'Halal Food', 'Restaurants', 'Recipes', 'Coffee', 'Fashion', 'Modest Fashion', 'Beauty', 'Skincare', 'Home Decor', 'Interior Design', 'Gardening', 'DIY', 'Technology', 'AI Tools', 'Software Tutorials', 'Cybersecurity', 'Web Development', 'Graphic Design', 'Photography', 'Video Editing', 'Content Creation', 'Podcasts', 'Books', 'Publishing', 'History', 'Science', 'Space', 'Animals & Pets', 'Marketplace Deals', 'Local Services', 'Events', 'Charity', 'Community Help', 'Clean Humor', 'Quotes', 'Motivation', 'Personal Development', 'Productivity'],
            'groups' => ['Local Community', 'Neighborhood Updates', 'City Discussions', 'Community Help', 'Lost & Found', 'Local Recommendations', 'Local Events', 'Local Services', 'Buy & Sell', 'Free Items', 'Jobs & Hiring', 'Remote Work', 'Freelance Work', 'Small Business Owners', 'Entrepreneurs', 'Business Networking', 'Startup Builders', 'Marketing Support', 'Social Media Growth', 'E-commerce Sellers', 'Affiliate Marketing', 'Content Creators', 'Video Creators', 'Writers', 'Designers', 'Web Developers', 'App Developers', 'AI Tools', 'Software Help', 'Tech Support', 'Cybersecurity Help', 'Computer Help', 'Mobile App Help', 'Laravel Developers', 'WordPress Help', 'SaaS Founders', 'Stock Market Learning', 'Day Trading', 'Long-Term Investing', 'Halal Investing', 'Personal Finance', 'Budgeting', 'Real Estate Investors', 'Rental Property Owners', 'Truck Drivers', 'Owner Operators', 'Dispatching', 'Freight & Logistics', 'Delivery Drivers', 'Aviation Students', 'Pilots', 'Flight Training', 'Travel Planning', 'Food Lovers', 'Halal Food Finds', 'Home Cooking', 'Recipe Exchange', 'Meal Prep', 'Restaurant Recommendations', 'Coffee Lovers', 'Healthy Eating', 'Fitness Motivation', 'Home Workouts', 'Walking & Running', 'Mental Wellness', 'Natural Remedies', 'Parenting Support', 'Marriage Advice', 'Family Life', 'Muslim Families', 'Youth Advice', 'Islamic Reminders', 'Qur’an Study', 'Hadith Study', 'Islamic History', 'Arabic Learning', 'English Learning', 'Study Support', 'Homeschooling', 'Book Readers', 'History Discussions', 'Car Enthusiasts', 'Motorcycle Riders', 'Home Decor', 'DIY Projects', 'Gardening', 'Pets & Animals', 'Photography', 'Video Editing', 'Clean Humor', 'Charity Support', 'Community Support'],
        ];

        foreach ($sets as $type => $names) {
            $sort = 11;
            foreach ($names as $name) {
                ContentCategory::updateOrCreate([
                    'slug' => Str::slug($type.' '.$name),
                ], [
                    'type' => $type === 'groups' ? 'community' : 'page',
                    'parent_id' => null,
                    'name' => $name,
                    'icon' => $this->iconFor($name),
                    'sort_order' => $sort,
                    'is_active' => true,
                ]);

                Category::updateOrCreate([
                    'scope' => $type,
                    'slug' => Str::slug($type.' '.$name),
                ], [
                    'name' => $name,
                ]);

                $sort += 11;
            }
        }
    }

    private function iconFor(string $name): string
    {
        return match (true) {
            str_contains($name, 'Islam') || str_contains($name, 'Qur') || str_contains($name, 'Hadith') => 'fa-solid fa-mosque',
            str_contains($name, 'Food') || str_contains($name, 'Coffee') || str_contains($name, 'Restaurant') => 'fa-solid fa-utensils',
            str_contains($name, 'Tech') || str_contains($name, 'AI') || str_contains($name, 'Software') => 'fa-solid fa-microchip',
            str_contains($name, 'Health') || str_contains($name, 'Fitness') => 'fa-solid fa-heart-pulse',
            str_contains($name, 'Business') || str_contains($name, 'Finance') || str_contains($name, 'Job') => 'fa-solid fa-briefcase',
            default => 'fa-solid fa-layer-group',
        };
    }
}
