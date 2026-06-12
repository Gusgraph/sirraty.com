<?php

// أَعُوذُ بِٱللَّهِ مِنْ الْشَيْطَانٍ الْرَجِيمٍ ✧ بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ ✧ اعوز بالله من الشياطين و ان يحضرون ✧ بسم الله الرحمن الرحيم ✧ الله لا إله إلا هو الحي القيوم
// Bismillahi ar-Rahmani ar-Rahim Audhu billahi min ash-shayatin wa an yahdurun Bismillah ar-Rahman ar-Rahim Allah la ilaha illa huwa al-hayy al-qayyum. Tamsa Allahu ala ayunihim
// version: 0.1.0
// ======================================================
// - Sirraty
// - Gusgraph
// - Author: Gus Kazem
// - https://Gusgraph.com
// - File Path: database/seeders/MarketCategorySeeder.php
// =====================================================

namespace Database\Seeders;

use App\Models\MarketCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class MarketCategorySeeder extends Seeder
{
    public function run(): void
    {
        $groups = [
            'Vehicles' => ['Cars', 'Trucks', 'Motorcycles', 'Boats', 'Trailers', 'Vehicle Parts', 'Vehicle Accessories', 'Tires & Wheels', 'Automotive Services'],
            'Property' => ['Homes for Rent', 'Apartments for Rent', 'Rooms for Rent', 'Commercial Property', 'Land', 'Real Estate for Sale', 'Vacation Rentals', 'Storage Space', 'Parking Space'],
            'Home & Furniture' => ['Furniture', 'Home Decor', 'Appliances', 'Kitchen Items', 'Lighting', 'Bedding', 'Rugs & Carpets', 'Garden Furniture', 'Outdoor Items'],
            'Electronics' => ['Phones', 'Computers', 'Laptops', 'Tablets', 'TVs', 'Cameras', 'Audio Equipment', 'Gaming Consoles', 'Smart Devices', 'Computer Parts'],
            'Clothing & Accessories' => ['Men’s Clothing', 'Women’s Clothing', 'Children’s Clothing', 'Shoes', 'Bags', 'Watches', 'Jewelry', 'Accessories', 'Modest Fashion'],
            'Baby & Kids' => ['Baby Clothing', 'Strollers', 'Car Seats', 'Baby Furniture', 'Toys', 'Kids Furniture', 'School Supplies'],
            'Books & Media' => ['Books', 'Islamic Books', 'Textbooks', 'Movies', 'Music', 'Magazines'],
            'Sports & Outdoors' => ['Sports Equipment', 'Fitness Equipment', 'Outdoor Gear', 'Camping Gear', 'Bicycles', 'Fishing Gear', 'Pool & Patio', 'Musical Instruments'],
            'Tools & Equipment' => ['Power Tools', 'Hand Tools', 'Hardware', 'Construction Tools', 'Lawn Equipment', 'Workshop Equipment'],
            'Business & Industrial' => ['Office Furniture', 'Office Supplies', 'Industrial Equipment', 'Restaurant Equipment', 'Farm Equipment', 'Medical Equipment', 'Beauty Equipment', 'Cleaning Equipment'],
            'Services' => ['Home Repair', 'Cleaning Services', 'Moving Services', 'Landscaping', 'Painting', 'Plumbing', 'Electrical', 'HVAC', 'Handyman Services', 'Delivery Services', 'Repair Services', 'Pet Services', 'Photography Services', 'Design Services', 'Web Services', 'Tutoring', 'Lessons & Training'],
            'Jobs' => ['Full-Time Jobs', 'Part-Time Jobs', 'Remote Jobs', 'Freelance Jobs', 'Contract Work', 'Temporary Work', 'Internships'],
            'Events' => ['Local Events', 'Family Events', 'Business Events', 'Community Events', 'Religious Events', 'Classes & Workshops'],
            'Pets' => ['Cats', 'Dogs', 'Birds', 'Fish', 'Pet Supplies', 'Pet Services'],
            'Free Items' => ['Free Furniture', 'Free Electronics', 'Free Clothes', 'Free Baby Items', 'Free Household Items'],
            'Food' => ['Halal Food', 'Homemade Food', 'Catering', 'Bakery Items', 'Grocery Items', 'Restaurant Listings'],
            'Islamic Items' => ['Prayer Items', 'Islamic Clothing', 'Islamic Decor', 'Islamic Gifts', 'Qur’an Copies', 'Educational Islamic Items'],
            'Health & Beauty' => ['Skincare', 'Haircare', 'Fragrance', 'Beauty Tools', 'Health Equipment'],
            'Farm & Garden' => ['Plants', 'Seeds', 'Garden Tools', 'Farm Supplies', 'Outdoor Equipment'],
            'Collectibles' => ['Collectibles', 'Antiques'],
        ];

        $sort = 11;
        foreach ($groups as $parentName => $children) {
            $parent = MarketCategory::updateOrCreate([
                'slug' => Str::slug($parentName),
            ], [
                'parent_id' => null,
                'name' => $parentName,
                'icon' => $this->iconFor($parentName),
                'sort_order' => $sort,
                'is_active' => true,
            ]);

            $childSort = 11;
            foreach ($children as $childName) {
                MarketCategory::updateOrCreate([
                    'slug' => Str::slug($parentName.' '.$childName),
                ], [
                    'parent_id' => $parent->id,
                    'name' => $childName,
                    'icon' => $this->iconFor($parentName),
                    'sort_order' => $childSort,
                    'is_active' => true,
                ]);
                $childSort += 11;
            }

            $sort += 11;
        }
    }

    private function iconFor(string $name): string
    {
        return match ($name) {
            'Vehicles' => 'fa-solid fa-car',
            'Property' => 'fa-solid fa-house',
            'Home & Furniture' => 'fa-solid fa-couch',
            'Electronics' => 'fa-solid fa-mobile-screen-button',
            'Clothing & Accessories' => 'fa-solid fa-shirt',
            'Baby & Kids' => 'fa-solid fa-child-reaching',
            'Books & Media' => 'fa-solid fa-book',
            'Sports & Outdoors' => 'fa-solid fa-person-running',
            'Tools & Equipment' => 'fa-solid fa-screwdriver-wrench',
            'Business & Industrial' => 'fa-solid fa-warehouse',
            'Services' => 'fa-solid fa-handshake',
            'Jobs' => 'fa-solid fa-briefcase',
            'Events' => 'fa-solid fa-calendar-days',
            'Pets' => 'fa-solid fa-paw',
            'Free Items' => 'fa-solid fa-gift',
            'Food' => 'fa-solid fa-utensils',
            'Islamic Items' => 'fa-solid fa-mosque',
            'Health & Beauty' => 'fa-solid fa-heart-pulse',
            'Farm & Garden' => 'fa-solid fa-seedling',
            'Collectibles' => 'fa-solid fa-gem',
            default => 'fa-solid fa-tag',
        };
    }
}
