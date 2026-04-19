<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\EnquiryController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\AmenityController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\SiteSettingController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\FloorPlanController;
use App\Http\Controllers\Admin\SmtpSettingController;

// Frontend Routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/projects', [HomeController::class, 'projects'])->name('projects');
Route::get('/projects/{slug}', [HomeController::class, 'projectDetail'])->name('project.detail');
Route::get('/gallery', [HomeController::class, 'gallery'])->name('gallery');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
Route::post('/contact', [HomeController::class, 'submitContact'])->name('contact.submit');
Route::post('/enquiry', [HomeController::class, 'submitEnquiry'])->name('enquiry.submit');
Route::get('/amenities', [HomeController::class, 'amenities'])->name('amenities');

// Admin Auth Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Projects
    Route::resource('projects', ProjectController::class);
    Route::delete('projects/{project}/gallery/{index}', [ProjectController::class, 'deleteGalleryImage'])->name('projects.gallery.delete');

    // Enquiries
    Route::get('/enquiries', [EnquiryController::class, 'index'])->name('enquiries.index');
    Route::get('/enquiries/{enquiry}', [EnquiryController::class, 'show'])->name('enquiries.show');
    Route::put('/enquiries/{enquiry}/status', [EnquiryController::class, 'updateStatus'])->name('enquiries.update-status');
    Route::delete('/enquiries/{enquiry}', [EnquiryController::class, 'destroy'])->name('enquiries.destroy');

    // Testimonials
    Route::resource('testimonials', TestimonialController::class);

    // Amenities
    Route::resource('amenities', AmenityController::class);

    // Gallery
    Route::resource('gallery', GalleryController::class);

    // Home Sliders
    Route::resource('sliders', HomeSliderController::class);

    // Floor Plans
    Route::resource('floor-plans', FloorPlanController::class);

    // Site Settings
    Route::get('/settings', [SiteSettingController::class, 'index'])->name('settings.index');
    Route::put('/settings', [SiteSettingController::class, 'update'])->name('settings.update');

    // SMTP Settings
    Route::get('/smtp', [SmtpSettingController::class, 'index'])->name('smtp.index');
    Route::put('/smtp', [SmtpSettingController::class, 'update'])->name('smtp.update');
    Route::post('/smtp/test', [SmtpSettingController::class, 'testEmail'])->name('smtp.test');
});
