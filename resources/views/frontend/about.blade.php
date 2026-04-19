@extends('frontend.layout')
@section('title', 'About Us')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>About <span style="color:var(--gold)">ARN Constructions</span></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">About Us</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div style="background: linear-gradient(135deg, var(--secondary), var(--dark)); border-radius: 15px; padding: 50px; color: #fff;">
                    <h2 style="color: var(--gold); margin-bottom: 25px; font-size: 2.2rem;">Our Story</h2>
                    <p style="font-size: 1.05rem; line-height: 1.9; opacity: 0.9;">{{ \App\Models\SiteSetting::get('about_us') }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                <h3 style="color: var(--secondary); margin-bottom: 20px;">Why Choose ARN Constructions?</h3>
                <div class="row g-3">
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-check-double"></i></div>
                            <h5>Quality Construction</h5>
                            <small class="text-muted">Premium materials & workmanship</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-clock"></i></div>
                            <h5>Timely Delivery</h5>
                            <small class="text-muted">On-time project completion</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-file-contract"></i></div>
                            <h5>RERA Compliant</h5>
                            <small class="text-muted">All projects RERA registered</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-hand-holding-usd"></i></div>
                            <h5>Best Pricing</h5>
                            <small class="text-muted">Competitive & transparent pricing</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-map-marked-alt"></i></div>
                            <h5>Prime Locations</h5>
                            <small class="text-muted">Strategically located projects</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-headset"></i></div>
                            <h5>24/7 Support</h5>
                            <small class="text-muted">Dedicated customer support</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6"><div class="stat-item"><div class="stat-icon"><i class="fas fa-calendar-alt"></i></div><h3>{{ \App\Models\SiteSetting::get('years_experience', '15+') }}</h3><p>Years of Experience</p></div></div>
            <div class="col-lg-3 col-md-6"><div class="stat-item"><div class="stat-icon"><i class="fas fa-building"></i></div><h3>{{ \App\Models\SiteSetting::get('projects_completed', '50+') }}</h3><p>Projects Completed</p></div></div>
            <div class="col-lg-3 col-md-6"><div class="stat-item"><div class="stat-icon"><i class="fas fa-smile"></i></div><h3>{{ \App\Models\SiteSetting::get('happy_customers', '5000+') }}</h3><p>Happy Customers</p></div></div>
            <div class="col-lg-3 col-md-6"><div class="stat-item"><div class="stat-icon"><i class="fas fa-trophy"></i></div><h3>20+</h3><p>Awards Won</p></div></div>
        </div>
    </div>
</section>

<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>Our Vision & Mission</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="icon-box mb-3" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--gold));display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-eye text-white fa-lg"></i>
                    </div>
                    <h4 style="color: var(--secondary);">Our Vision</h4>
                    <p class="text-muted mt-3">To be the most trusted and innovative real estate developer, creating spaces that inspire and enrich lives. We envision transforming the urban landscape with sustainable and futuristic developments.</p>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="p-4 bg-white rounded-4 shadow-sm h-100">
                    <div class="icon-box mb-3" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--gold));display:flex;align-items:center;justify-content:center;">
                        <i class="fas fa-bullseye text-white fa-lg"></i>
                    </div>
                    <h4 style="color: var(--secondary);">Our Mission</h4>
                    <p class="text-muted mt-3">To deliver exceptional quality homes and commercial spaces at competitive prices, maintaining the highest standards of integrity, transparency, and customer satisfaction in every project we undertake.</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
