# ANR Constructions Website

A full-featured real estate website with Super Admin panel built with **Laravel**, **MySQL (XAMPP)**, **Bootstrap 5**, and **Font Awesome**.

## 🚀 Quick Start

### 1. Start XAMPP MySQL
Make sure XAMPP MySQL is running on port 3306.

### 2. Run the development server
```bash
cd /Users/sunil/Desktop/anrcontrustions/anr-website
php artisan serve
```

### 3. Access the website
- **Frontend:** http://localhost:8000
- **Admin Panel:** http://localhost:8000/admin/login

## 🔐 Admin Login Credentials
- **Email:** admin@anrconstructions.com
- **Password:** admin@123

## 📋 Features

### Frontend (Public Website)
- ✅ Home page with hero slider, featured projects, stats counter, testimonials
- ✅ About Us page with vision, mission, and company info
- ✅ Projects listing with filters (type, status, BHK)
- ✅ Project detail page with gallery, amenities, floor plans, location map
- ✅ Gallery page with category/project filters
- ✅ Amenities page
- ✅ Contact page with enquiry form
- ✅ Quick enquiry form on every page
- ✅ WhatsApp floating button
- ✅ Responsive design (mobile, tablet, desktop)
- ✅ SEO-friendly meta tags

### Super Admin Panel
- ✅ **Dashboard** - Stats overview, recent enquiries, quick stats
- ✅ **Projects** - Full CRUD (Create, Read, Update, Delete) with image uploads
- ✅ **Floor Plans** - Manage floor plans per project with area & pricing
- ✅ **Amenities** - Manage amenities with icons
- ✅ **Gallery** - Upload and manage gallery images with categories
- ✅ **Home Sliders** - Manage homepage hero sliders
- ✅ **Testimonials** - Manage customer testimonials with ratings
- ✅ **Enquiries** - View, filter, and manage leads with status updates
- ✅ **Site Settings** - Update all site content (phone, email, address, social links, about text, stats)

## 🗂️ Project Structure

```
anr-website/
├── app/
│   ├── Http/Controllers/
│   │   ├── HomeController.php          # Frontend controller
│   │   └── Admin/
│   │       ├── AuthController.php      # Admin login/logout
│   │       ├── DashboardController.php # Admin dashboard
│   │       ├── ProjectController.php   # Projects CRUD
│   │       ├── EnquiryController.php   # Enquiries management
│   │       ├── TestimonialController.php
│   │       ├── AmenityController.php
│   │       ├── GalleryController.php
│   │       ├── HomeSliderController.php
│   │       ├── FloorPlanController.php
│   │       └── SiteSettingController.php
│   └── Models/
│       ├── Project.php
│       ├── Enquiry.php
│       ├── Testimonial.php
│       ├── Amenity.php
│       ├── Gallery.php
│       ├── HomeSlider.php
│       ├── FloorPlan.php
│       └── SiteSetting.php
├── database/
│   ├── migrations/    # 8 custom tables
│   └── seeders/       # Sample data + admin user
├── resources/views/
│   ├── frontend/      # Public website views
│   │   ├── layout.blade.php
│   │   ├── index.blade.php
│   │   ├── about.blade.php
│   │   ├── projects.blade.php
│   │   ├── project-detail.blade.php
│   │   ├── gallery.blade.php
│   │   ├── contact.blade.php
│   │   └── amenities.blade.php
│   └── admin/         # Admin panel views
│       ├── layout.blade.php
│       ├── auth/login.blade.php
│       ├── dashboard.blade.php
│       ├── projects/
│       ├── enquiries/
│       ├── testimonials/
│       ├── amenities/
│       ├── gallery/
│       ├── sliders/
│       ├── floor-plans/
│       └── settings/
└── routes/
    └── web.php        # All routes (frontend + admin)
```

## 🗄️ Database Tables
| Table | Purpose |
|-------|---------|
| users | Admin users |
| projects | All real estate projects |
| floor_plans | Floor plans per project |
| amenities | Project amenities |
| galleries | Image gallery |
| home_sliders | Homepage hero sliders |
| testimonials | Customer testimonials |
| enquiries | Contact/enquiry form submissions |
| site_settings | Dynamic site configuration |

## ⚙️ Tech Stack
- **Backend:** Laravel (PHP 8.5)
- **Database:** MySQL (XAMPP)
- **Frontend:** Bootstrap 5, Font Awesome 6, Animate.css
- **Fonts:** Playfair Display + Poppins (Google Fonts)
