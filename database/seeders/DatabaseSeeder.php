<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\SiteSetting;
use App\Models\HomeSlider;
use App\Models\Project;
use App\Models\Amenity;
use App\Models\FloorPlan;
use App\Models\Testimonial;
use App\Models\Gallery;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'admin@anrconstructions.com',
            'password' => bcrypt('admin@123'),
            'email_verified_at' => now(),
        ]);

        // Site Settings
        $settings = [
            ['key' => 'site_name', 'value' => 'ARN Constructions', 'type' => 'text', 'group' => 'general', 'label' => 'Site Name'],
            ['key' => 'site_tagline', 'value' => 'Building Dreams, Delivering Excellence', 'type' => 'text', 'group' => 'general', 'label' => 'Site Tagline'],
            ['key' => 'site_description', 'value' => 'ARN Constructions is a premier real estate developer committed to building quality homes and commercial spaces with modern amenities and excellent craftsmanship.', 'type' => 'textarea', 'group' => 'general', 'label' => 'Site Description'],
            ['key' => 'phone_primary', 'value' => '+91 98765 43210', 'type' => 'text', 'group' => 'contact', 'label' => 'Primary Phone'],
            ['key' => 'phone_secondary', 'value' => '+91 98765 43211', 'type' => 'text', 'group' => 'contact', 'label' => 'Secondary Phone'],
            ['key' => 'email_primary', 'value' => 'info@anrconstructions.com', 'type' => 'text', 'group' => 'contact', 'label' => 'Primary Email'],
            ['key' => 'email_sales', 'value' => 'sales@anrconstructions.com', 'type' => 'text', 'group' => 'contact', 'label' => 'Sales Email'],
            ['key' => 'address', 'value' => 'ARN Towers, Plot No. 45, Jubilee Hills, Hyderabad, Telangana - 500033', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Address'],
            ['key' => 'whatsapp', 'value' => '919876543210', 'type' => 'text', 'group' => 'social', 'label' => 'WhatsApp Number'],
            ['key' => 'facebook', 'value' => 'https://facebook.com/anrconstructions', 'type' => 'text', 'group' => 'social', 'label' => 'Facebook URL'],
            ['key' => 'instagram', 'value' => 'https://instagram.com/anrconstructions', 'type' => 'text', 'group' => 'social', 'label' => 'Instagram URL'],
            ['key' => 'youtube', 'value' => 'https://youtube.com/@anrconstructions', 'type' => 'text', 'group' => 'social', 'label' => 'YouTube URL'],
            ['key' => 'linkedin', 'value' => 'https://linkedin.com/company/anrconstructions', 'type' => 'text', 'group' => 'social', 'label' => 'LinkedIn URL'],
            ['key' => 'about_us', 'value' => 'ARN Constructions has been a trusted name in the real estate industry for over 15 years. We specialize in building premium residential apartments, luxury villas, and commercial spaces. Our commitment to quality, transparency, and customer satisfaction has made us one of the most preferred builders in the region.', 'type' => 'textarea', 'group' => 'about', 'label' => 'About Us'],
            ['key' => 'years_experience', 'value' => '15+', 'type' => 'text', 'group' => 'about', 'label' => 'Years of Experience'],
            ['key' => 'projects_completed', 'value' => '50+', 'type' => 'text', 'group' => 'about', 'label' => 'Projects Completed'],
            ['key' => 'happy_customers', 'value' => '5000+', 'type' => 'text', 'group' => 'about', 'label' => 'Happy Customers'],
            ['key' => 'google_maps_embed', 'value' => '', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Google Maps Embed URL'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }

        // Home Sliders
        $sliders = [
            ['title' => 'Welcome to ARN Constructions', 'subtitle' => 'Building Dreams Into Reality', 'description' => 'Discover premium apartments and villas crafted with excellence. Experience luxury living at its finest.', 'image' => 'sliders/default-hero-1.jpg', 'button_text' => 'Explore Projects', 'button_link' => '/projects', 'is_active' => true, 'sort_order' => 1],
            ['title' => 'ARN Skyline Towers', 'subtitle' => 'Luxury 2 & 3 BHK Apartments', 'description' => 'Experience world-class amenities and breathtaking views at our flagship project in the heart of the city.', 'image' => 'sliders/default-hero-2.jpg', 'button_text' => 'View Details', 'button_link' => '/projects', 'is_active' => true, 'sort_order' => 2],
            ['title' => 'Limited Time Offer', 'subtitle' => 'Book Now & Get Special Prices', 'description' => 'Don\'t miss this opportunity. Pre-launch prices available on select units. Schedule a site visit today!', 'image' => 'sliders/default-hero-3.jpg', 'button_text' => 'Enquire Now', 'button_link' => '/contact', 'is_active' => true, 'sort_order' => 3],
        ];

        foreach ($sliders as $slider) {
            HomeSlider::create($slider);
        }

        // Sample Projects
        $projects = [
            [
                'name' => 'ARN Skyline Towers',
                'slug' => 'anr-skyline-towers',
                'location' => 'Jubilee Hills, Hyderabad',
                'city' => 'Hyderabad',
                'short_description' => 'Premium 2 & 3 BHK luxury apartments in the heart of Jubilee Hills with panoramic city views.',
                'description' => '<h2>ARN Skyline Towers - Where Luxury Meets Lifestyle</h2><p>ARN Skyline Towers is our flagship residential project offering premium 2 & 3 BHK apartments in one of the most coveted locations in Hyderabad. Each apartment is meticulously designed to maximize natural light, ventilation, and living space.</p><p>The project features a grand entrance lobby, landscaped gardens, a state-of-the-art clubhouse, and a wide range of amenities that cater to every member of your family.</p><h3>Key Highlights</h3><ul><li>Vaastu compliant designs</li><li>Earthquake resistant structure</li><li>Premium fittings and finishes</li><li>Smart home features</li></ul>',
                'type' => 'apartment',
                'status' => 'ongoing',
                'price_min' => 8500000,
                'price_max' => 15000000,
                'bhk_options' => '2,3',
                'total_units' => 250,
                'area_min' => 1250,
                'area_max' => 1850,
                'rera_id' => 'P02400XXXXX',
                'possession_date' => '2027-12-31',
                'featured_image' => 'projects/default-project.jpg',
                'is_featured' => true,
                'is_active' => true,
                'highlights' => ['Vaastu Compliant', 'Smart Home Features', 'Panoramic Views', 'Premium Clubhouse'],
            ],
            [
                'name' => 'ARN Green Valley Villas',
                'slug' => 'anr-green-valley-villas',
                'location' => 'Gachibowli, Hyderabad',
                'city' => 'Hyderabad',
                'short_description' => 'Exclusive 4 BHK independent villas with private gardens in a gated community.',
                'description' => '<h2>ARN Green Valley Villas - Your Private Paradise</h2><p>Experience the joy of independent living with ARN Green Valley Villas. Nestled in a serene environment, these 4 BHK villas offer the perfect blend of privacy, luxury, and community living.</p><p>Each villa comes with a private garden, car porch, and premium interiors. The gated community ensures complete security and peace of mind for your family.</p>',
                'type' => 'villa',
                'status' => 'ongoing',
                'price_min' => 25000000,
                'price_max' => 40000000,
                'bhk_options' => '4',
                'total_units' => 50,
                'area_min' => 3000,
                'area_max' => 4500,
                'featured_image' => 'projects/default-project.jpg',
                'is_featured' => true,
                'is_active' => true,
                'highlights' => ['Private Garden', 'Gated Community', 'Independent Villa', 'Premium Interiors'],
            ],
            [
                'name' => 'ARN Pearl Heights',
                'slug' => 'anr-pearl-heights',
                'location' => 'HITEC City, Hyderabad',
                'city' => 'Hyderabad',
                'short_description' => 'Modern 3 BHK apartments with world-class amenities near IT corridor.',
                'description' => '<h2>ARN Pearl Heights - Smart Living Redefined</h2><p>Located strategically near the IT corridor, ARN Pearl Heights offers the perfect work-life balance. These modern 3 BHK apartments are designed for the tech-savvy generation with smart home features and sustainable design.</p>',
                'type' => 'apartment',
                'status' => 'upcoming',
                'price_min' => 12000000,
                'price_max' => 18000000,
                'bhk_options' => '3',
                'total_units' => 180,
                'area_min' => 1500,
                'area_max' => 2000,
                'featured_image' => 'projects/default-project.jpg',
                'is_featured' => true,
                'is_active' => true,
                'highlights' => ['IT Corridor Location', 'Smart Home', 'Sustainable Design', 'Metro Connected'],
            ],
            [
                'name' => 'ARN Lake View Residency',
                'slug' => 'anr-lake-view-residency',
                'location' => 'Kukatpally, Hyderabad',
                'city' => 'Hyderabad',
                'short_description' => 'Affordable 2 BHK apartments with serene lake views and excellent connectivity.',
                'description' => '<h2>ARN Lake View Residency - Affordable Luxury</h2><p>Enjoy the beauty of lakeside living without compromising on quality. ARN Lake View Residency offers well-designed 2 BHK apartments at an attractive price point.</p>',
                'type' => 'apartment',
                'status' => 'completed',
                'price_min' => 5500000,
                'price_max' => 7500000,
                'bhk_options' => '2',
                'total_units' => 120,
                'area_min' => 1050,
                'area_max' => 1350,
                'featured_image' => 'projects/default-project.jpg',
                'is_featured' => false,
                'is_active' => true,
                'highlights' => ['Lake Views', 'Affordable Pricing', 'Ready to Move', 'Excellent Connectivity'],
            ],
            [
                'name' => 'ARN Business Hub',
                'slug' => 'anr-business-hub',
                'location' => 'Madhapur, Hyderabad',
                'city' => 'Hyderabad',
                'short_description' => 'Premium commercial office spaces in the heart of Madhapur IT district.',
                'description' => '<h2>ARN Business Hub - Where Business Thrives</h2><p>A state-of-the-art commercial complex designed for modern businesses. Located in the prime IT district, ARN Business Hub offers flexible office spaces with top-notch infrastructure.</p>',
                'type' => 'commercial',
                'status' => 'ongoing',
                'price_min' => 15000000,
                'price_max' => 50000000,
                'bhk_options' => null,
                'total_units' => 80,
                'area_min' => 1000,
                'area_max' => 5000,
                'featured_image' => 'projects/default-project.jpg',
                'is_featured' => false,
                'is_active' => true,
                'highlights' => ['Prime Location', 'Modern Infrastructure', 'Flexible Spaces', 'Ample Parking'],
            ],
        ];

        foreach ($projects as $project) {
            Project::create($project);
        }

        // Amenities
        $amenities = [
            ['name' => 'Swimming Pool', 'icon' => 'fa-swimming-pool', 'description' => 'Temperature controlled swimming pool with separate kids section', 'project_id' => 1, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Gymnasium', 'icon' => 'fa-dumbbell', 'description' => 'Fully equipped modern gym with personal trainers', 'project_id' => 1, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Club House', 'icon' => 'fa-building', 'description' => 'Luxurious clubhouse with indoor games and party hall', 'project_id' => 1, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Children Play Area', 'icon' => 'fa-child', 'description' => 'Safe and fun play area for children', 'project_id' => 1, 'is_active' => true, 'sort_order' => 4],
            ['name' => 'Jogging Track', 'icon' => 'fa-running', 'description' => 'Dedicated jogging track through landscaped gardens', 'project_id' => 1, 'is_active' => true, 'sort_order' => 5],
            ['name' => 'Multipurpose Hall', 'icon' => 'fa-users', 'description' => 'Spacious hall for events and celebrations', 'project_id' => 1, 'is_active' => true, 'sort_order' => 6],
            ['name' => 'Power Backup', 'icon' => 'fa-bolt', 'description' => '24/7 power backup for all common areas and apartments', 'project_id' => null, 'is_active' => true, 'sort_order' => 7],
            ['name' => 'Security', 'icon' => 'fa-shield-alt', 'description' => 'Round-the-clock security with CCTV surveillance', 'project_id' => null, 'is_active' => true, 'sort_order' => 8],
            ['name' => 'Intercom', 'icon' => 'fa-phone', 'description' => 'Video intercom facility for each apartment', 'project_id' => null, 'is_active' => true, 'sort_order' => 9],
            ['name' => 'Car Parking', 'icon' => 'fa-car', 'description' => 'Covered car parking with EV charging stations', 'project_id' => null, 'is_active' => true, 'sort_order' => 10],
            ['name' => 'Rainwater Harvesting', 'icon' => 'fa-tint', 'description' => 'Sustainable rainwater harvesting system', 'project_id' => null, 'is_active' => true, 'sort_order' => 11],
            ['name' => 'Landscaped Gardens', 'icon' => 'fa-tree', 'description' => 'Beautifully landscaped gardens and green spaces', 'project_id' => null, 'is_active' => true, 'sort_order' => 12],
        ];

        foreach ($amenities as $amenity) {
            Amenity::create($amenity);
        }

        // Floor Plans
        $floorPlans = [
            ['project_id' => 1, 'name' => '2 BHK Classic', 'bhk_type' => '2 BHK', 'area_sqft' => 1250, 'price' => 8500000, 'is_active' => true, 'sort_order' => 1],
            ['project_id' => 1, 'name' => '2 BHK Premium', 'bhk_type' => '2 BHK', 'area_sqft' => 1450, 'price' => 10500000, 'is_active' => true, 'sort_order' => 2],
            ['project_id' => 1, 'name' => '3 BHK Deluxe', 'bhk_type' => '3 BHK', 'area_sqft' => 1650, 'price' => 13000000, 'is_active' => true, 'sort_order' => 3],
            ['project_id' => 1, 'name' => '3 BHK Ultra Luxury', 'bhk_type' => '3 BHK', 'area_sqft' => 1850, 'price' => 15000000, 'is_active' => true, 'sort_order' => 4],
            ['project_id' => 2, 'name' => '4 BHK Villa Standard', 'bhk_type' => '4 BHK', 'area_sqft' => 3000, 'price' => 25000000, 'is_active' => true, 'sort_order' => 1],
            ['project_id' => 2, 'name' => '4 BHK Villa Premium', 'bhk_type' => '4 BHK', 'area_sqft' => 4500, 'price' => 40000000, 'is_active' => true, 'sort_order' => 2],
            ['project_id' => 3, 'name' => '3 BHK Smart', 'bhk_type' => '3 BHK', 'area_sqft' => 1500, 'price' => 12000000, 'is_active' => true, 'sort_order' => 1],
            ['project_id' => 3, 'name' => '3 BHK Ultra', 'bhk_type' => '3 BHK', 'area_sqft' => 2000, 'price' => 18000000, 'is_active' => true, 'sort_order' => 2],
        ];

        foreach ($floorPlans as $fp) {
            FloorPlan::create($fp);
        }

        // Testimonials
        $testimonials = [
            ['name' => 'Rajesh Kumar', 'designation' => 'Software Engineer', 'company' => 'TCS', 'testimonial' => 'ARN Constructions delivered our dream home on time with excellent quality. The attention to detail in every aspect of construction is commendable. We are extremely happy with our decision to choose ARN.', 'rating' => 5, 'project_id' => 4, 'is_active' => true, 'sort_order' => 1],
            ['name' => 'Priya Sharma', 'designation' => 'Business Owner', 'company' => 'Self Employed', 'testimonial' => 'The team at ARN is professional and transparent. From booking to possession, everything was smooth. The amenities provided are world-class and the location is perfect for our family.', 'rating' => 5, 'project_id' => 4, 'is_active' => true, 'sort_order' => 2],
            ['name' => 'Venkat Rao', 'designation' => 'Retired Govt. Officer', 'company' => '', 'testimonial' => 'I invested in ARN Lake View Residency for my retirement. The serene lake views and peaceful environment are exactly what I was looking for. Best decision ever!', 'rating' => 5, 'project_id' => 4, 'is_active' => true, 'sort_order' => 3],
            ['name' => 'Anita Desai', 'designation' => 'Doctor', 'company' => 'Apollo Hospitals', 'testimonial' => 'Quality construction, timely delivery, and excellent after-sales service. ARN Constructions sets the benchmark in real estate. Highly recommended!', 'rating' => 4, 'project_id' => 1, 'is_active' => true, 'sort_order' => 4],
        ];

        foreach ($testimonials as $t) {
            Testimonial::create($t);
        }
    }
}
