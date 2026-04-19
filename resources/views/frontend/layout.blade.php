<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="{{ $settings['site_description'] ?? 'ARN Constructions - Building Dreams, Delivering Excellence' }}">
    <title>@yield('title', 'ARN Constructions') | {{ $settings['site_name'] ?? 'ARN Constructions' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        :root {
            --primary: #b8860b;
            --primary-dark: #8B6914;
            --secondary: #1a1a2e;
            --dark: #0f0f23;
            --light: #f8f9fa;
            --gold: #d4a843;
            --gold-light: #f0d78c;
            --gray: #6c757d;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; color: #333; overflow-x: hidden; }
        h1, h2, h3, h4, h5 { font-family: 'Playfair Display', serif; }

        /* Top Bar */
        .top-bar { background: var(--secondary); color: #fff; padding: 8px 0; font-size: 13px; }
        .top-bar a { color: var(--gold-light); text-decoration: none; }
        .top-bar a:hover { color: #fff; }
        .top-bar .social-links a { color: #fff; margin-left: 12px; font-size: 14px; transition: color 0.3s; }
        .top-bar .social-links a:hover { color: var(--gold); }

        /* Navbar */
        .main-nav { background: #fff; box-shadow: 0 2px 20px rgba(0,0,0,0.08); position: sticky; top: 0; z-index: 1000; }
        .main-nav .navbar-brand { font-family: 'Playfair Display', serif; font-weight: 700; font-size: 28px; color: var(--secondary); }
        .main-nav .navbar-brand span { color: var(--primary); }
        .main-nav .nav-link { color: var(--secondary); font-weight: 500; padding: 15px 18px !important; transition: all 0.3s; font-size: 15px; }
        .main-nav .nav-link:hover, .main-nav .nav-link.active { color: var(--primary); }
        .btn-enquire { background: linear-gradient(135deg, var(--primary), var(--gold)); color: #fff !important; border: none; padding: 10px 28px !important; border-radius: 50px; font-weight: 600; transition: all 0.3s; }
        .btn-enquire:hover { transform: translateY(-2px); box-shadow: 0 5px 20px rgba(184,134,11,0.4); }

        /* Hero Slider */
        .hero-slider { position: relative; height: 90vh; min-height: 600px; }
        .hero-slide { height: 90vh; min-height: 600px; background-size: cover; background-position: center; position: relative; display: flex; align-items: center; }
        .hero-slide::before { content: ''; position: absolute; inset: 0; background: linear-gradient(135deg, rgba(15,15,35,0.85) 0%, rgba(26,26,46,0.6) 100%); }
        .hero-slide .container { position: relative; z-index: 2; }
        .hero-content { color: #fff; max-width: 700px; }
        .hero-content h1 { font-size: 3.5rem; font-weight: 700; margin-bottom: 20px; line-height: 1.2; }
        .hero-content h1 span { color: var(--gold); }
        .hero-content p { font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9; }
        .btn-primary-custom { background: linear-gradient(135deg, var(--primary), var(--gold)); color: #fff; padding: 14px 40px; border: none; border-radius: 50px; font-weight: 600; font-size: 16px; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary-custom:hover { transform: translateY(-3px); box-shadow: 0 8px 25px rgba(184,134,11,0.5); color: #fff; }
        .btn-outline-custom { border: 2px solid #fff; color: #fff; padding: 12px 35px; border-radius: 50px; font-weight: 600; font-size: 16px; transition: all 0.3s; text-decoration: none; display: inline-block; background: transparent; }
        .btn-outline-custom:hover { background: #fff; color: var(--secondary); }

        /* Sections */
        .section-padding { padding: 80px 0; }
        .section-title { text-align: center; margin-bottom: 50px; }
        .section-title h2 { font-size: 2.5rem; color: var(--secondary); margin-bottom: 15px; position: relative; display: inline-block; }
        .section-title h2::after { content: ''; position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); width: 80px; height: 3px; background: linear-gradient(135deg, var(--primary), var(--gold)); }
        .section-title p { color: var(--gray); font-size: 1.1rem; max-width: 600px; margin: 20px auto 0; }

        /* Project Cards */
        .project-card { border: none; border-radius: 15px; overflow: hidden; box-shadow: 0 5px 30px rgba(0,0,0,0.1); transition: all 0.4s; background: #fff; }
        .project-card:hover { transform: translateY(-10px); box-shadow: 0 15px 40px rgba(0,0,0,0.2); }
        .project-card .card-img-top { height: 250px; object-fit: cover; }
        .project-card .status-badge { position: absolute; top: 15px; left: 15px; padding: 5px 15px; border-radius: 50px; font-size: 12px; font-weight: 600; color: #fff; text-transform: uppercase; }
        .status-ongoing { background: #28a745; }
        .status-completed { background: #007bff; }
        .status-upcoming { background: #ffc107; color: #333 !important; }
        .project-card .card-body { padding: 25px; }
        .project-card .card-title { font-size: 1.3rem; color: var(--secondary); margin-bottom: 10px; }
        .project-card .location { color: var(--gray); font-size: 14px; margin-bottom: 10px; }
        .project-card .price { color: var(--primary); font-size: 1.1rem; font-weight: 600; margin-bottom: 15px; }
        .project-card .features { display: flex; gap: 15px; flex-wrap: wrap; margin-bottom: 15px; }
        .project-card .features span { background: #f0f0f0; padding: 4px 12px; border-radius: 20px; font-size: 12px; color: var(--secondary); }

        /* Stats Counter */
        .stats-section { background: linear-gradient(135deg, var(--secondary), var(--dark)); color: #fff; padding: 60px 0; }
        .stat-item { text-align: center; padding: 20px; }
        .stat-item .stat-icon { font-size: 2.5rem; color: var(--gold); margin-bottom: 15px; }
        .stat-item h3 { font-size: 3rem; font-weight: 700; color: var(--gold); }
        .stat-item p { font-size: 1rem; opacity: 0.8; margin-top: 5px; }

        /* Amenities */
        .amenity-item { text-align: center; padding: 30px 20px; border-radius: 15px; background: #fff; box-shadow: 0 3px 20px rgba(0,0,0,0.06); transition: all 0.3s; }
        .amenity-item:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.12); }
        .amenity-item .icon-box { width: 70px; height: 70px; border-radius: 50%; background: linear-gradient(135deg, var(--primary), var(--gold)); display: flex; align-items: center; justify-content: center; margin: 0 auto 15px; }
        .amenity-item .icon-box i { color: #fff; font-size: 1.5rem; }
        .amenity-item h5 { color: var(--secondary); font-size: 1rem; }

        /* Testimonials */
        .testimonial-card { background: #fff; border-radius: 15px; padding: 35px; box-shadow: 0 5px 25px rgba(0,0,0,0.08); position: relative; }
        .testimonial-card .quote-icon { position: absolute; top: 20px; right: 25px; font-size: 3rem; color: var(--gold-light); opacity: 0.3; }
        .testimonial-card .stars { color: #ffc107; margin-bottom: 15px; }
        .testimonial-card p { font-style: italic; color: #555; line-height: 1.8; margin-bottom: 20px; }
        .testimonial-card .author { display: flex; align-items: center; gap: 15px; }
        .testimonial-card .author img { width: 50px; height: 50px; border-radius: 50%; object-fit: cover; }
        .testimonial-card .author-info h6 { margin: 0; color: var(--secondary); }
        .testimonial-card .author-info small { color: var(--gray); }

        /* CTA Section */
        .cta-section { background: linear-gradient(135deg, var(--primary), var(--gold)); color: #fff; padding: 80px 0; text-align: center; }
        .cta-section h2 { font-size: 2.5rem; margin-bottom: 20px; }
        .cta-section p { font-size: 1.2rem; margin-bottom: 30px; opacity: 0.9; }
        .btn-white { background: #fff; color: var(--primary); padding: 14px 40px; border-radius: 50px; font-weight: 600; border: none; transition: all 0.3s; text-decoration: none; display: inline-block; }
        .btn-white:hover { background: var(--secondary); color: #fff; }

        /* Footer */
        .footer { background: var(--secondary); color: #fff; padding: 60px 0 0; }
        .footer h5 { color: var(--gold); margin-bottom: 25px; font-size: 1.2rem; }
        .footer a { color: #ccc; text-decoration: none; transition: color 0.3s; }
        .footer a:hover { color: var(--gold); }
        .footer ul { list-style: none; padding: 0; }
        .footer ul li { margin-bottom: 10px; }
        .footer .contact-info li { display: flex; gap: 10px; align-items: flex-start; }
        .footer .contact-info i { color: var(--gold); margin-top: 4px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding: 20px 0; margin-top: 40px; text-align: center; }
        .footer-bottom p { margin: 0; font-size: 14px; opacity: 0.7; }

        /* WhatsApp Float */
        .whatsapp-float { position: fixed; bottom: 30px; right: 30px; z-index: 999; width: 60px; height: 60px; border-radius: 50%; background: #25D366; color: #fff; display: flex; align-items: center; justify-content: center; font-size: 28px; box-shadow: 0 5px 20px rgba(37,211,102,0.4); transition: all 0.3s; text-decoration: none; }
        .whatsapp-float:hover { transform: scale(1.1); color: #fff; }

        /* Enquiry Form */
        .enquiry-form { background: #fff; border-radius: 15px; padding: 35px; box-shadow: 0 5px 30px rgba(0,0,0,0.1); }
        .enquiry-form h3 { color: var(--secondary); margin-bottom: 25px; }
        .enquiry-form .form-control { border-radius: 10px; padding: 12px 15px; border: 1px solid #e0e0e0; }
        .enquiry-form .form-control:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(184,134,11,0.15); }

        /* Page Header */
        .page-header { background: linear-gradient(135deg, var(--secondary), var(--dark)); color: #fff; padding: 120px 0 60px; text-align: center; }
        .page-header h1 { font-size: 2.8rem; margin-bottom: 15px; }
        .page-header .breadcrumb { justify-content: center; }
        .page-header .breadcrumb-item a { color: var(--gold); text-decoration: none; }
        .page-header .breadcrumb-item.active { color: #ccc; }

        /* Hero Badge */
        .hero-badge {
            display: inline-block;
            padding: 6px 16px;
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            font-size: 11px;
            font-family: 'Poppins', sans-serif;
            color: rgba(255,255,255,0.6);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(4px);
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .hero-content h1 { font-size: 2rem; }
            .section-title h2 { font-size: 1.8rem; }
            .hero-slider { height: 70vh; min-height: 450px; }
            .hero-slide { height: 70vh; min-height: 450px; }
            .top-bar { display: none; }
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <span><i class="fas fa-phone-alt"></i> {{ $settings['phone_primary'] ?? '' }}</span>
                    <span class="ms-3"><i class="fas fa-envelope"></i> {{ $settings['email_primary'] ?? '' }}</span>
                </div>
                <div class="col-md-4 text-end social-links">
                    <a href="{{ $settings['facebook'] ?? '#' }}" target="_blank"><i class="fab fa-facebook-f"></i></a>
                    <a href="{{ $settings['instagram'] ?? '#' }}" target="_blank"><i class="fab fa-instagram"></i></a>
                    <a href="{{ $settings['youtube'] ?? '#' }}" target="_blank"><i class="fab fa-youtube"></i></a>
                    <a href="{{ $settings['linkedin'] ?? '#' }}" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg main-nav">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('images/logo.jpeg') }}" alt="ARN Constructions" style="height: 55px; margin-right: 8px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('projects*') ? 'active' : '' }}" href="{{ route('projects') }}">Projects</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('gallery') ? 'active' : '' }}" href="{{ route('gallery') }}">Gallery</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('amenities') ? 'active' : '' }}" href="{{ route('amenities') }}">Amenities</a></li>
                    <li class="nav-item"><a class="nav-link {{ request()->is('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
                    <li class="nav-item ms-3"><a class="btn btn-enquire" href="{{ route('contact') }}">Enquire Now</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container">
            <h2>Ready to Find Your Dream Home?</h2>
            <p>Get in touch with us today and let us help you find the perfect property</p>
            <a href="{{ route('contact') }}" class="btn-white btn">Schedule a Visit</a>
            <a href="tel:{{ $settings['phone_primary'] ?? '' }}" class="btn btn-outline-light ms-3 rounded-pill px-4 py-3">Call Us Now</a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <img src="{{ asset('images/logo.jpeg') }}" alt="ARN Constructions" style="height: 50px; margin-bottom: 10px;"><br>
                    <h5><span style="color:#fff">ARN</span> Constructions</h5>
                    <p>{{ $settings['site_description'] ?? '' }}</p>
                    <div class="social-links mt-3">
                        <a href="{{ $settings['facebook'] ?? '#' }}" class="me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="{{ $settings['instagram'] ?? '#' }}" class="me-3"><i class="fab fa-instagram"></i></a>
                        <a href="{{ $settings['youtube'] ?? '#' }}" class="me-3"><i class="fab fa-youtube"></i></a>
                        <a href="{{ $settings['linkedin'] ?? '#' }}"><i class="fab fa-linkedin-in"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="{{ route('home') }}">Home</a></li>
                        <li><a href="{{ route('about') }}">About Us</a></li>
                        <li><a href="{{ route('projects') }}">Projects</a></li>
                        <li><a href="{{ route('gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Our Projects</h5>
                    <ul>
                        @foreach($footerProjects as $p)
                        <li><a href="{{ route('project.detail', $p->slug) }}">{{ $p->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5>Contact Us</h5>
                    <ul class="contact-info">
                        <li><i class="fas fa-map-marker-alt"></i> <span>{{ $settings['address'] ?? '' }}</span></li>
                        <li><i class="fas fa-phone-alt"></i> <span>{{ $settings['phone_primary'] ?? '' }}</span></li>
                        <li><i class="fas fa-envelope"></i> <span>{{ $settings['email_primary'] ?? '' }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <p>&copy; {{ date('Y') }} ARN Constructions. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Float -->
    <a href="https://wa.me/{{ $settings['whatsapp'] ?? '' }}" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
