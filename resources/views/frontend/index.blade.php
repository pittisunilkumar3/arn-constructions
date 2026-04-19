@extends('frontend.layout')

@section('title', 'Home')

@section('content')
<!-- Hero Section with Particle Effect -->
<section class="hero-slider" id="heroSlider" style="position: relative;">
    <!-- Particle Canvas -->
    <canvas id="particleCanvas" style="position: absolute; inset: 0; z-index: 1; width: 100%; height: 100%; cursor: crosshair;"></canvas>

    <!-- Slide Content Over Canvas -->
    @foreach($sliders as $index => $slider)
    <div class="hero-slide" data-slide-index="{{ $index }}" style="background-image: url('{{ $slider->image ? Storage::url($slider->image) : asset('images/hero-default.jpg') }}');">
        <div class="container">
            <div class="hero-content animate__animated animate__fadeInUp">
                <h1>{!! $slider->title !!} <span>{{ $slider->subtitle }}</span></h1>
                <p>{{ $slider->description }}</p>
                <div class="d-flex gap-3 flex-wrap">
                    @if($slider->button_text)
                    <a href="{{ $slider->button_link }}" class="btn-primary-custom">{{ $slider->button_text }}</a>
                    @endif
                    <a href="{{ route('contact') }}" class="btn-outline-custom">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @if($sliders->isEmpty())
    <div class="hero-slide" data-slide-index="0" style="background: linear-gradient(135deg, #1a1a2e, #0f0f23);">
        <div class="container">
            <div class="hero-content">
                <span class="hero-badge">Premium Construction & Real Estate</span>
                <h1>Building <span>Dreams</span> Into Reality</h1>
                <p>Discover premium apartments and villas crafted with excellence. Experience innovation in every structure we build.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="{{ route('projects') }}" class="btn-primary-custom">
                        Explore Projects
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('contact') }}" class="btn-outline-custom">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Scroll Indicator -->
    <div style="position: absolute; bottom: 30px; left: 50%; transform: translateX(-50%); z-index: 10; display: flex; flex-direction: column; align-items: center; gap: 8px; opacity: 0.4; pointer-events: none;">
        <span style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.2em; color: #fff;">Move your cursor</span>
        <i class="fas fa-mouse-pointer" style="color: #fff; font-size: 16px;"></i>
    </div>

    <!-- Debug Overlay -->
    <div id="particleDebug" style="position: absolute; bottom: 15px; right: 15px; z-index: 10; font-size: 11px; color: rgba(255,255,255,0.15); font-family: monospace; text-align: right; pointer-events: none;"></div>
</section>

<!-- Featured Projects -->
<section class="section-padding" id="featured-projects">
    <div class="container">
        <div class="section-title">
            <h2>Our Featured Projects</h2>
            <p>Discover our handpicked selection of premium properties designed for modern living</p>
        </div>
        <div class="row g-4">
            @foreach($featuredProjects as $project)
            <div class="col-lg-4 col-md-6">
                <div class="project-card position-relative">
                    @if($project->featured_image)
                    <img src="{{ Storage::url($project->featured_image) }}" class="card-img-top" alt="{{ $project->name }}">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1a1a2e, #2d2d5e);">
                        <div class="text-center text-white p-4">
                            <i class="fas fa-building fa-3x mb-3" style="color: var(--gold);"></i>
                            <h5>{{ $project->name }}</h5>
                        </div>
                    </div>
                    @endif
                    <span class="status-badge status-{{ $project->status }}">{{ ucfirst($project->status) }}</span>
                    <div class="card-body">
                        <h4 class="card-title">{{ $project->name }}</h4>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $project->location }}</p>
                        <p class="price">{{ $project->price_range }}</p>
                        <div class="features">
                            @if($project->bhk_options)
                            @foreach(explode(',', $project->bhk_options) as $bhk)
                            <span>{{ $bhk }} BHK</span>
                            @endforeach
                            @endif
                            @if($project->area_min)
                            <span>{{ $project->area_range }}</span>
                            @endif
                        </div>
                        <a href="{{ route('project.detail', $project->slug) }}" class="btn-primary-custom w-100 text-center d-block">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="text-center mt-5">
            <a href="{{ route('projects') }}" class="btn-primary-custom">View All Projects <i class="fas fa-arrow-right ms-2"></i></a>
        </div>
    </div>
</section>

<!-- Stats Counter -->
<section class="stats-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-calendar-alt"></i></div>
                    <h3>{{ $settings['years_experience'] ?? '15+' }}</h3>
                    <p>Years of Experience</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-building"></i></div>
                    <h3>{{ $settings['projects_completed'] ?? '50+' }}</h3>
                    <p>Projects Completed</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-smile"></i></div>
                    <h3>{{ $settings['happy_customers'] ?? '5000+' }}</h3>
                    <p>Happy Customers</p>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-item">
                    <div class="stat-icon"><i class="fas fa-hard-hat"></i></div>
                    <h3>Ongoing</h3>
                    <p>Projects in Progress</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Preview -->
<section class="section-padding bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <div style="background: linear-gradient(135deg, var(--secondary), var(--dark)); border-radius: 15px; padding: 60px; color: #fff; min-height: 400px; display: flex; flex-direction: column; justify-content: center;">
                    <h2 style="color: var(--gold); margin-bottom: 20px; font-size: 2.5rem;">Building Dreams Since {{ $settings['years_experience'] ?? '15+' }}</h2>
                    <p style="font-size: 1.1rem; line-height: 1.8; opacity: 0.9;">{{ $settings['about_us'] ?? '' }}</p>
                    <a href="{{ route('about') }}" class="btn-primary-custom mt-3" style="width: fit-content;">Learn More About Us</a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-check-circle"></i></div>
                            <h5>RERA Registered</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-award"></i></div>
                            <h5>Quality Assured</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-clock"></i></div>
                            <h5>Timely Delivery</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="amenity-item">
                            <div class="icon-box"><i class="fas fa-handshake"></i></div>
                            <h5>Transparent Deals</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ongoing Projects -->
@if($ongoingProjects->count() > 0)
<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>Ongoing Projects</h2>
            <p>Invest in our ongoing projects and be part of something extraordinary</p>
        </div>
        <div class="row g-4">
            @foreach($ongoingProjects as $project)
            <div class="col-lg-4 col-md-6">
                <div class="project-card position-relative">
                    @if($project->featured_image)
                    <img src="{{ Storage::url($project->featured_image) }}" class="card-img-top" alt="{{ $project->name }}">
                    @else
                    <div class="card-img-top d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1a1a2e, #2d2d5e);">
                        <div class="text-center text-white p-4">
                            <i class="fas fa-building fa-3x mb-3" style="color: var(--gold);"></i>
                            <h5>{{ $project->name }}</h5>
                        </div>
                    </div>
                    @endif
                    <span class="status-badge status-ongoing">Ongoing</span>
                    <div class="card-body">
                        <h4 class="card-title">{{ $project->name }}</h4>
                        <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $project->location }}</p>
                        <p class="price">{{ $project->price_range }}</p>
                        <a href="{{ route('project.detail', $project->slug) }}" class="btn-primary-custom w-100 text-center d-block">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Testimonials -->
@if($testimonials->count() > 0)
<section class="section-padding bg-light">
    <div class="container">
        <div class="section-title">
            <h2>What Our Customers Say</h2>
            <p>Hear from the people who trusted us with their dream homes</p>
        </div>
        <div class="row g-4">
            @foreach($testimonials as $t)
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <i class="fas fa-quote-right quote-icon"></i>
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star{{ $i <= $t->rating ? '' : '-half-alt' }}"></i>
                        @endfor
                    </div>
                    <p>"{{ $t->testimonial }}"</p>
                    <div class="author">
                        <div class="author-info">
                            <h6>{{ $t->name }}</h6>
                            <small>{{ $t->designation }}{{ $t->company ? ', ' . $t->company : '' }}</small>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Quick Enquiry -->
<section class="section-padding">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="section-title">
                    <h2>Have a Question?</h2>
                    <p>Fill the form below and our team will get back to you within 24 hours</p>
                </div>
                <div class="enquiry-form">
                    <form action="{{ route('enquiry.submit') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <input type="text" name="name" class="form-control" placeholder="Your Name *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="tel" name="phone" class="form-control" placeholder="Phone Number *" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" class="form-control" placeholder="Email Address">
                            </div>
                            <div class="col-md-6">
                                <select name="project_id" class="form-control">
                                    <option value="">Select Project (Optional)</option>
                                    @foreach($enquiryProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <textarea name="message" class="form-control" rows="4" placeholder="Your Message"></textarea>
                            </div>
                            <div class="col-12 text-center">
                                <button type="submit" class="btn-primary-custom border-0" style="cursor:pointer;">Submit Enquiry</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    // ============================================
    // 1. Simple Auto-Slider
    // ============================================
    const slides = document.querySelectorAll('.hero-slide');
    let currentSlide = 0;
    if (slides.length > 1) {
        setInterval(() => {
            slides[currentSlide].style.display = 'none';
            currentSlide = (currentSlide + 1) % slides.length;
            slides[currentSlide].style.display = 'flex';
        }, 5000);
    }

    // ============================================
    // 2. Particle Effect System (Anti-Gravity Canvas)
    // Adapted for ARN Constructions – gold/white theme
    // ============================================
    (function() {
        const canvas = document.getElementById('particleCanvas');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        const container = document.getElementById('heroSlider');
        if (!container) return;

        // --- Configuration ---
        const PARTICLE_DENSITY = 0.00015;
        const BG_PARTICLE_DENSITY = 0.00005;
        const MOUSE_RADIUS = 180;
        const RETURN_SPEED = 0.08;
        const DAMPING = 0.90;
        const REPULSION_STRENGTH = 1.2;

        // --- State ---
        let particles = [];
        let bgParticles = [];
        const mouse = { x: -1000, y: -1000, isActive: false };
        let frameId = 0;
        let lastTime = 0;
        let totalEntities = 0;

        // --- Helpers ---
        const randomRange = (min, max) => Math.random() * (max - min) + min;

        // --- Initialize ---
        function initParticles(w, h) {
            // Main interactive particles
            const count = Math.floor(w * h * PARTICLE_DENSITY);
            particles = [];
            for (let i = 0; i < count; i++) {
                const x = Math.random() * w;
                const y = Math.random() * h;
                particles.push({
                    x, y, originX: x, originY: y,
                    vx: 0, vy: 0,
                    size: randomRange(1, 2.5),
                    // Gold, gold-light, or white particles to match ARN theme
                    color: Math.random() > 0.85
                        ? (Math.random() > 0.5 ? '#d4a843' : '#f0d78c')
                        : '#ffffff',
                    angle: Math.random() * Math.PI * 2
                });
            }

            // Background ambient (stars/dust)
            const bgCount = Math.floor(w * h * BG_PARTICLE_DENSITY);
            bgParticles = [];
            for (let i = 0; i < bgCount; i++) {
                bgParticles.push({
                    x: Math.random() * w,
                    y: Math.random() * h,
                    vx: (Math.random() - 0.5) * 0.2,
                    vy: (Math.random() - 0.5) * 0.2,
                    size: randomRange(0.5, 1.5),
                    alpha: randomRange(0.1, 0.4),
                    phase: Math.random() * Math.PI * 2
                });
            }
            totalEntities = count + bgCount;
        }

        function resize() {
            const rect = container.getBoundingClientRect();
            const dpr = window.devicePixelRatio || 1;
            canvas.width = rect.width * dpr;
            canvas.height = rect.height * dpr;
            canvas.style.width = rect.width + 'px';
            canvas.style.height = rect.height + 'px';
            ctx.scale(dpr, dpr);
            initParticles(rect.width, rect.height);
        }

        // --- Animation Loop ---
        function animate(time) {
            const rect = container.getBoundingClientRect();
            const w = rect.width;
            const h = rect.height;

            const delta = time - lastTime;
            lastTime = time;
            const fps = delta > 0 ? Math.round(1000 / delta) : 0;

            ctx.clearRect(0, 0, w, h);

            // 1. Pulsating radial glow (gold tint for ARN)
            const cx = w / 2, cy = h / 2;
            const pulseOpacity = Math.sin(time * 0.0008) * 0.035 + 0.085;
            const grad = ctx.createRadialGradient(cx, cy, 0, cx, cy, Math.max(w, h) * 0.7);
            grad.addColorStop(0, `rgba(212, 168, 67, ${pulseOpacity})`); // gold glow
            grad.addColorStop(1, 'rgba(0, 0, 0, 0)');
            ctx.fillStyle = grad;
            ctx.fillRect(0, 0, w, h);

            // 2. Background drifting stars
            ctx.fillStyle = '#ffffff';
            for (let i = 0; i < bgParticles.length; i++) {
                const p = bgParticles[i];
                p.x += p.vx;
                p.y += p.vy;
                if (p.x < 0) p.x = w;
                if (p.x > w) p.x = 0;
                if (p.y < 0) p.y = h;
                if (p.y > h) p.y = 0;
                const twinkle = Math.sin(time * 0.002 + p.phase) * 0.5 + 0.5;
                ctx.globalAlpha = p.alpha * (0.3 + 0.7 * twinkle);
                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                ctx.fill();
            }
            ctx.globalAlpha = 1.0;

            // 3. Main particles – physics
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                const dx = mouse.x - p.x;
                const dy = mouse.y - p.y;
                const dist = Math.sqrt(dx * dx + dy * dy);

                if (mouse.isActive && dist < MOUSE_RADIUS) {
                    const force = (MOUSE_RADIUS - dist) / MOUSE_RADIUS * REPULSION_STRENGTH;
                    const nx = dx / dist, ny = dy / dist;
                    p.vx -= nx * force * 5;
                    p.vy -= ny * force * 5;
                }

                p.vx += (p.originX - p.x) * RETURN_SPEED;
                p.vy += (p.originY - p.y) * RETURN_SPEED;
            }

            // 4. Collision resolution
            for (let i = 0; i < particles.length; i++) {
                for (let j = i + 1; j < particles.length; j++) {
                    const p1 = particles[i], p2 = particles[j];
                    const dx = p2.x - p1.x, dy = p2.y - p1.y;
                    const distSq = dx * dx + dy * dy;
                    const minDist = p1.size + p2.size;
                    if (distSq < minDist * minDist) {
                        const dist = Math.sqrt(distSq);
                        if (dist > 0.01) {
                            const nx = dx / dist, ny = dy / dist;
                            const overlap = minDist - dist;
                            p1.x -= nx * overlap * 0.5;
                            p1.y -= ny * overlap * 0.5;
                            p2.x += nx * overlap * 0.5;
                            p2.y += ny * overlap * 0.5;
                            const dvx = p1.vx - p2.vx, dvy = p1.vy - p2.vy;
                            const velNormal = dvx * nx + dvy * ny;
                            if (velNormal > 0) {
                                const m1 = p1.size, m2 = p2.size;
                                const rest = 0.85;
                                const imp = (-(1 + rest) * velNormal) / (1/m1 + 1/m2);
                                p1.vx += (imp * nx) / m1;
                                p1.vy += (imp * ny) / m1;
                                p2.vx -= (imp * nx) / m2;
                                p2.vy -= (imp * ny) / m2;
                            }
                        }
                    }
                }
            }

            // 5. Integration & draw
            for (let i = 0; i < particles.length; i++) {
                const p = particles[i];
                p.vx *= DAMPING;
                p.vy *= DAMPING;
                p.x += p.vx;
                p.y += p.vy;

                const vel = Math.sqrt(p.vx * p.vx + p.vy * p.vy);
                const opacity = Math.min(0.3 + vel * 0.1, 1);

                ctx.beginPath();
                ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                if (p.color === '#ffffff') {
                    ctx.fillStyle = `rgba(255, 255, 255, ${opacity})`;
                } else {
                    // Gold particles with alpha
                    ctx.fillStyle = p.color === '#d4a843'
                        ? `rgba(212, 168, 67, ${opacity})`
                        : `rgba(240, 215, 140, ${opacity})`;
                }
                ctx.fill();
            }

            // Debug overlay
            const debugEl = document.getElementById('particleDebug');
            if (debugEl) {
                debugEl.innerHTML = `<p>${totalEntities} entities</p><p>${fps} FPS</p>`;
            }

            frameId = requestAnimationFrame(animate);
        }

        // --- Events ---
        container.addEventListener('mousemove', function(e) {
            const rect = container.getBoundingClientRect();
            mouse.x = e.clientX - rect.left;
            mouse.y = e.clientY - rect.top;
            mouse.isActive = true;
        });
        container.addEventListener('mouseleave', function() {
            mouse.isActive = false;
        });
        // Touch support for mobile
        container.addEventListener('touchmove', function(e) {
            const rect = container.getBoundingClientRect();
            const touch = e.touches[0];
            mouse.x = touch.clientX - rect.left;
            mouse.y = touch.clientY - rect.top;
            mouse.isActive = true;
        }, { passive: true });
        container.addEventListener('touchend', function() {
            mouse.isActive = false;
        });

        window.addEventListener('resize', resize);
        resize();
        frameId = requestAnimationFrame(animate);
    })();
</script>
@endsection
