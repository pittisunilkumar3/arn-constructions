-- ============================================================
-- ANR Constructions Website - Complete Database Setup
-- ============================================================
-- This file creates all tables and inserts sample data.
-- Import via phpMyAdmin or command line:
--   mysql -u username -p database_name < anr_database.sql
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";
SET NAMES utf8mb4;

-- ============================================================
-- TABLE: users (Admin users)
-- ============================================================
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Default admin: admin@anrconstructions.com / admin@123
-- Password will be updated by the installer
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin', 'admin@anrconstructions.com', NOW(), '$2y$12$ppzfMbZoO53KR2bwikNA3OPf88a4aVPD9Oh9XKC.yO/YJCxNXLbPG', NOW(), NOW());

-- ============================================================
-- TABLE: password_reset_tokens
-- ============================================================
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: sessions
-- ============================================================
CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: cache
-- ============================================================
CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: cache_locks
-- ============================================================
CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: jobs
-- ============================================================
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: job_batches
-- ============================================================
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: failed_jobs
-- ============================================================
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: projects
-- ============================================================
CREATE TABLE IF NOT EXISTS `projects` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `short_description` text NOT NULL,
  `description` longtext NOT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'apartment',
  `status` varchar(255) NOT NULL DEFAULT 'ongoing',
  `price_min` decimal(12,2) DEFAULT NULL,
  `price_max` decimal(12,2) DEFAULT NULL,
  `bhk_options` varchar(255) DEFAULT NULL,
  `total_units` int DEFAULT NULL,
  `area_min` decimal(10,2) DEFAULT NULL,
  `area_max` decimal(10,2) DEFAULT NULL,
  `rera_id` varchar(255) DEFAULT NULL,
  `possession_date` date DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` json DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `brochure` varchar(255) DEFAULT NULL,
  `highlights` json DEFAULT NULL,
  `is_featured` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `projects_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `projects` (`id`, `name`, `slug`, `location`, `city`, `short_description`, `description`, `type`, `status`, `price_min`, `price_max`, `bhk_options`, `total_units`, `area_min`, `area_max`, `rera_id`, `possession_date`, `featured_image`, `gallery`, `video_url`, `brochure`, `highlights`, `is_featured`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ARN Skyline Towers', 'anr-skyline-towers', 'Jubilee Hills, Hyderabad', 'Hyderabad', 'Premium 2 & 3 BHK luxury apartments in the heart of Jubilee Hills with panoramic city views.', '<h2>ARN Skyline Towers - Where Luxury Meets Lifestyle</h2><p>ARN Skyline Towers is our flagship residential project offering premium 2 & 3 BHK apartments in one of the most coveted locations in Hyderabad.</p><h3>Key Highlights</h3><ul><li>Vaastu compliant designs</li><li>Earthquake resistant structure</li><li>Premium fittings and finishes</li><li>Smart home features</li></ul>', 'apartment', 'ongoing', 8500000.00, 15000000.00, '2,3', 250, 1250.00, 1850.00, 'P02400XXXXX', '2027-12-31', 'projects/default-project.jpg', NULL, NULL, NULL, '[\"Vaastu Compliant\",\"Smart Home Features\",\"Panoramic Views\",\"Premium Clubhouse\"]', 1, 1, NOW(), NOW()),
(2, 'ARN Green Valley Villas', 'anr-green-valley-villas', 'Gachibowli, Hyderabad', 'Hyderabad', 'Exclusive 4 BHK independent villas with private gardens in a gated community.', '<h2>ARN Green Valley Villas - Your Private Paradise</h2><p>Experience the joy of independent living with ARN Green Valley Villas. These 4 BHK villas offer the perfect blend of privacy, luxury, and community living.</p>', 'villa', 'ongoing', 25000000.00, 40000000.00, '4', 50, 3000.00, 4500.00, NULL, NULL, 'projects/default-project.jpg', NULL, NULL, NULL, '[\"Private Garden\",\"Gated Community\",\"Independent Villa\",\"Premium Interiors\"]', 1, 1, NOW(), NOW()),
(3, 'ARN Pearl Heights', 'anr-pearl-heights', 'HITEC City, Hyderabad', 'Hyderabad', 'Modern 3 BHK apartments with world-class amenities near IT corridor.', '<h2>ARN Pearl Heights - Smart Living Redefined</h2><p>Located strategically near the IT corridor, ARN Pearl Heights offers the perfect work-life balance with smart home features and sustainable design.</p>', 'apartment', 'upcoming', 12000000.00, 18000000.00, '3', 180, 1500.00, 2000.00, NULL, NULL, 'projects/default-project.jpg', NULL, NULL, NULL, '[\"IT Corridor Location\",\"Smart Home\",\"Sustainable Design\",\"Metro Connected\"]', 1, 1, NOW(), NOW()),
(4, 'ARN Lake View Residency', 'anr-lake-view-residency', 'Kukatpally, Hyderabad', 'Hyderabad', 'Affordable 2 BHK apartments with serene lake views and excellent connectivity.', '<h2>ARN Lake View Residency - Affordable Luxury</h2><p>Enjoy the beauty of lakeside living without compromising on quality.</p>', 'apartment', 'completed', 5500000.00, 7500000.00, '2', 120, 1050.00, 1350.00, NULL, NULL, 'projects/default-project.jpg', NULL, NULL, NULL, '[\"Lake Views\",\"Affordable Pricing\",\"Ready to Move\",\"Excellent Connectivity\"]', 0, 1, NOW(), NOW()),
(5, 'ARN Business Hub', 'anr-business-hub', 'Madhapur, Hyderabad', 'Hyderabad', 'Premium commercial office spaces in the heart of Madhapur IT district.', '<h2>ARN Business Hub - Where Business Thrives</h2><p>A state-of-the-art commercial complex designed for modern businesses.</p>', 'commercial', 'ongoing', 15000000.00, 50000000.00, NULL, 80, 1000.00, 5000.00, NULL, NULL, 'projects/default-project.jpg', NULL, NULL, NULL, '[\"Prime Location\",\"Modern Infrastructure\",\"Flexible Spaces\",\"Ample Parking\"]', 0, 1, NOW(), NOW());

-- ============================================================
-- TABLE: amenities
-- ============================================================
CREATE TABLE IF NOT EXISTS `amenities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `project_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `amenities_project_id_foreign` (`project_id`),
  CONSTRAINT `amenities_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `amenities` (`name`, `icon`, `description`, `project_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Swimming Pool', 'fa-swimming-pool', 'Temperature controlled swimming pool with separate kids section', 1, 1, 1, NOW(), NOW()),
('Gymnasium', 'fa-dumbbell', 'Fully equipped modern gym with personal trainers', 1, 1, 2, NOW(), NOW()),
('Club House', 'fa-building', 'Luxurious clubhouse with indoor games and party hall', 1, 1, 3, NOW(), NOW()),
('Children Play Area', 'fa-child', 'Safe and fun play area for children', 1, 1, 4, NOW(), NOW()),
('Jogging Track', 'fa-running', 'Dedicated jogging track through landscaped gardens', 1, 1, 5, NOW(), NOW()),
('Multipurpose Hall', 'fa-users', 'Spacious hall for events and celebrations', 1, 1, 6, NOW(), NOW()),
('Power Backup', 'fa-bolt', '24/7 power backup for all common areas and apartments', NULL, 1, 7, NOW(), NOW()),
('Security', 'fa-shield-alt', 'Round-the-clock security with CCTV surveillance', NULL, 1, 8, NOW(), NOW()),
('Intercom', 'fa-phone', 'Video intercom facility for each apartment', NULL, 1, 9, NOW(), NOW()),
('Car Parking', 'fa-car', 'Covered car parking with EV charging stations', NULL, 1, 10, NOW(), NOW()),
('Rainwater Harvesting', 'fa-tint', 'Sustainable rainwater harvesting system', NULL, 1, 11, NOW(), NOW()),
('Landscaped Gardens', 'fa-tree', 'Beautifully landscaped gardens and green spaces', NULL, 1, 12, NOW(), NOW());

-- ============================================================
-- TABLE: enquiries
-- ============================================================
CREATE TABLE IF NOT EXISTS `enquiries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'general',
  `project_id` bigint unsigned DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'new',
  `source` varchar(255) NOT NULL DEFAULT 'website',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `enquiries_project_id_foreign` (`project_id`),
  CONSTRAINT `enquiries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: floor_plans
-- ============================================================
CREATE TABLE IF NOT EXISTS `floor_plans` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `project_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `bhk_type` varchar(255) NOT NULL,
  `area_sqft` decimal(10,2) NOT NULL,
  `price` decimal(12,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `floor_plans_project_id_foreign` (`project_id`),
  CONSTRAINT `floor_plans_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `floor_plans` (`project_id`, `name`, `bhk_type`, `area_sqft`, `price`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, '2 BHK Classic', '2 BHK', 1250.00, 8500000.00, 1, 1, NOW(), NOW()),
(1, '2 BHK Premium', '2 BHK', 1450.00, 10500000.00, 1, 2, NOW(), NOW()),
(1, '3 BHK Deluxe', '3 BHK', 1650.00, 13000000.00, 1, 3, NOW(), NOW()),
(1, '3 BHK Ultra Luxury', '3 BHK', 1850.00, 15000000.00, 1, 4, NOW(), NOW()),
(2, '4 BHK Villa Standard', '4 BHK', 3000.00, 25000000.00, 1, 1, NOW(), NOW()),
(2, '4 BHK Villa Premium', '4 BHK', 4500.00, 40000000.00, 1, 2, NOW(), NOW()),
(3, '3 BHK Smart', '3 BHK', 1500.00, 12000000.00, 1, 1, NOW(), NOW()),
(3, '3 BHK Ultra', '3 BHK', 2000.00, 18000000.00, 1, 2, NOW(), NOW());

-- ============================================================
-- TABLE: galleries
-- ============================================================
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'general',
  `project_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `galleries_project_id_foreign` (`project_id`),
  CONSTRAINT `galleries_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- TABLE: home_sliders
-- ============================================================
CREATE TABLE IF NOT EXISTS `home_sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) NOT NULL,
  `button_text` varchar(255) DEFAULT NULL,
  `button_link` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `home_sliders` (`title`, `subtitle`, `description`, `image`, `button_text`, `button_link`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Welcome to ARN Constructions', 'Building Dreams Into Reality', 'Discover premium apartments and villas crafted with excellence.', 'sliders/default-hero-1.jpg', 'Explore Projects', '/projects', 1, 1, NOW(), NOW()),
('ARN Skyline Towers', 'Luxury 2 & 3 BHK Apartments', 'Experience world-class amenities and breathtaking views at our flagship project.', 'sliders/default-hero-2.jpg', 'View Details', '/projects', 1, 2, NOW(), NOW()),
('Limited Time Offer', 'Book Now & Get Special Prices', 'Pre-launch prices available on select units. Schedule a site visit today!', 'sliders/default-hero-3.jpg', 'Enquire Now', '/contact', 1, 3, NOW(), NOW());

-- ============================================================
-- TABLE: testimonials
-- ============================================================
CREATE TABLE IF NOT EXISTS `testimonials` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `company` varchar(255) DEFAULT NULL,
  `testimonial` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `rating` int NOT NULL DEFAULT '5',
  `project_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `sort_order` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `testimonials_project_id_foreign` (`project_id`),
  CONSTRAINT `testimonials_project_id_foreign` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `testimonials` (`name`, `designation`, `company`, `testimonial`, `rating`, `project_id`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
('Rajesh Kumar', 'Software Engineer', 'TCS', 'ARN Constructions delivered our dream home on time with excellent quality. The attention to detail in every aspect of construction is commendable.', 5, 4, 1, 1, NOW(), NOW()),
('Priya Sharma', 'Business Owner', 'Self Employed', 'The team at ARN is professional and transparent. From booking to possession, everything was smooth. The amenities provided are world-class.', 5, 4, 1, 2, NOW(), NOW()),
('Venkat Rao', 'Retired Govt. Officer', '', 'I invested in ARN Lake View Residency for my retirement. The serene lake views and peaceful environment are exactly what I was looking for.', 5, 4, 1, 3, NOW(), NOW()),
('Anita Desai', 'Doctor', 'Apollo Hospitals', 'Quality construction, timely delivery, and excellent after-sales service. ARN Constructions sets the benchmark in real estate. Highly recommended!', 4, 1, 1, 4, NOW(), NOW());

-- ============================================================
-- TABLE: site_settings
-- ============================================================
CREATE TABLE IF NOT EXISTS `site_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `group` varchar(255) NOT NULL DEFAULT 'general',
  `label` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `site_settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `site_settings` (`key`, `value`, `type`, `group`, `label`, `created_at`, `updated_at`) VALUES
('site_name', 'ARN Constructions', 'text', 'general', 'Site Name', NOW(), NOW()),
('site_tagline', 'Building Dreams, Delivering Excellence', 'text', 'general', 'Site Tagline', NOW(), NOW()),
('site_description', 'ARN Constructions is a premier real estate developer committed to building quality homes and commercial spaces with modern amenities and excellent craftsmanship.', 'textarea', 'general', 'Site Description', NOW(), NOW()),
('phone_primary', '+91 98765 43210', 'text', 'contact', 'Primary Phone', NOW(), NOW()),
('phone_secondary', '+91 98765 43211', 'text', 'contact', 'Secondary Phone', NOW(), NOW()),
('email_primary', 'info@anrconstructions.com', 'text', 'contact', 'Primary Email', NOW(), NOW()),
('email_sales', 'sales@anrconstructions.com', 'text', 'contact', 'Sales Email', NOW(), NOW()),
('address', 'ARN Towers, Plot No. 45, Jubilee Hills, Hyderabad, Telangana - 500033', 'textarea', 'contact', 'Address', NOW(), NOW()),
('whatsapp', '919876543210', 'text', 'social', 'WhatsApp Number', NOW(), NOW()),
('facebook', 'https://facebook.com/anrconstructions', 'text', 'social', 'Facebook URL', NOW(), NOW()),
('instagram', 'https://instagram.com/anrconstructions', 'text', 'social', 'Instagram URL', NOW(), NOW()),
('youtube', 'https://youtube.com/@anrconstructions', 'text', 'social', 'YouTube URL', NOW(), NOW()),
('linkedin', 'https://linkedin.com/company/anrconstructions', 'text', 'social', 'LinkedIn URL', NOW(), NOW()),
('about_us', 'ARN Constructions has been a trusted name in the real estate industry for over 15 years. We specialize in building premium residential apartments, luxury villas, and commercial spaces.', 'textarea', 'about', 'About Us', NOW(), NOW()),
('years_experience', '15+', 'text', 'about', 'Years of Experience', NOW(), NOW()),
('projects_completed', '50+', 'text', 'about', 'Projects Completed', NOW(), NOW()),
('happy_customers', '5000+', 'text', 'about', 'Happy Customers', NOW(), NOW()),
('google_maps_embed', '', 'textarea', 'contact', 'Google Maps Embed URL', NOW(), NOW());

-- ============================================================
-- DONE!
-- ============================================================
COMMIT;

SELECT 'ANR Constructions database setup completed successfully!' AS status;
SELECT COUNT(*) AS total_tables FROM information_schema.tables WHERE table_schema = DATABASE();
