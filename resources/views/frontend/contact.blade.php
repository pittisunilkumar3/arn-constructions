@extends('frontend.layout')
@section('title', 'Contact Us')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Contact <span style="color:var(--gold)">Us</span></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Contact</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box mx-auto mb-3" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--gold));display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-map-marker-alt text-white fa-lg"></i>
                        </div>
                        <h5 style="color:var(--secondary);">Visit Us</h5>
                        <p class="text-muted mb-0">{{ $settings['address'] ?? '' }}</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box mx-auto mb-3" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--gold));display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-phone-alt text-white fa-lg"></i>
                        </div>
                        <h5 style="color:var(--secondary);">Call Us</h5>
                        <p class="text-muted mb-1">{{ $settings['phone_primary'] ?? '' }}</p>
                        <p class="text-muted mb-0">{{ $settings['phone_secondary'] ?? '' }}</p>
                    </div>
                </div>
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4 text-center">
                        <div class="icon-box mx-auto mb-3" style="width:60px;height:60px;border-radius:50%;background:linear-gradient(135deg,var(--primary),var(--gold));display:flex;align-items:center;justify-content:center;">
                            <i class="fas fa-envelope text-white fa-lg"></i>
                        </div>
                        <h5 style="color:var(--secondary);">Email Us</h5>
                        <p class="text-muted mb-1">{{ $settings['email_primary'] ?? '' }}</p>
                        <p class="text-muted mb-0">{{ $settings['email_sales'] ?? '' }}</p>
                    </div>
                </div>

                <!-- WhatsApp CTA -->
                @if(!empty($settings['whatsapp']))
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body p-4 text-center" style="background:#25D366;border-radius:12px;">
                        <i class="fab fa-whatsapp text-white fa-2x mb-2"></i>
                        <h5 class="text-white mb-1">Chat on WhatsApp</h5>
                        <p class="text-white mb-2" style="opacity:0.9;font-size:14px;">Quick response guaranteed</p>
                        <a href="https://wa.me/{{ $settings['whatsapp'] }}" target="_blank" class="btn btn-light btn-sm rounded-pill px-4">
                            <i class="fab fa-whatsapp me-1"></i> Chat Now
                        </a>
                    </div>
                </div>
                @endif
            </div>
            <div class="col-lg-8">
                <div class="enquiry-form">
                    <h3>Send Us a Message</h3>

                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                        @csrf
                        <input type="hidden" name="type" value="contact">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Your Name *</label>
                                <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Phone Number *</label>
                                <input type="tel" name="phone" class="form-control" placeholder="+91 98765 43210" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Email Address</label>
                                <input type="email" name="email" class="form-control" placeholder="john@example.com">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small">Interested Project</label>
                                <select name="project_id" class="form-control">
                                    <option value="">Select Project (Optional)</option>
                                    @foreach($footerProjects as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">Subject</label>
                                <input type="text" name="subject" class="form-control" placeholder="How can we help you?">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small">Your Message *</label>
                                <textarea name="message" class="form-control" rows="5" placeholder="Tell us about your requirements..." required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn-primary-custom border-0" style="cursor:pointer;" id="submitBtn">
                                    Send Message <i class="fas fa-paper-plane ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Map -->
        @if(!empty($settings['google_maps_embed']))
        <div class="mt-5" style="border-radius:15px;overflow:hidden;">
            {!! $settings['google_maps_embed'] !!}
        </div>
        @endif
    </div>
</section>
@endsection

@section('scripts')
<script>
document.getElementById('contactForm').addEventListener('submit', function() {
    const btn = document.getElementById('submitBtn');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Sending...';
});
</script>
@endsection
