@extends('frontend.layout')
@section('title', $project->name)

@section('content')
<div class="page-header">
    <div class="container">
        <h1>{{ $project->name }}</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('projects') }}">Projects</a></li>
                <li class="breadcrumb-item active">{{ $project->name }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Project Overview -->
<section class="section-padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Gallery -->
                @if($project->featured_image || count($project->gallery ?? []) > 0)
                <div id="projectGallery" class="mb-4">
                    <div style="border-radius:15px;overflow:hidden;">
                        @if($project->featured_image)
                        <img src="{{ upload_url($project->featured_image) }}" class="w-100" style="max-height:500px;object-fit:cover;" alt="{{ $project->name }}">
                        @endif
                    </div>
                    @if(count($project->gallery ?? []) > 0)
                    <div class="row g-2 mt-2">
                        @foreach($project->gallery as $img)
                        <div class="col-3">
                            <img src="{{ upload_url($img) }}" class="w-100 rounded" style="height:120px;object-fit:cover;" alt="Gallery">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif

                <!-- Description -->
                <div class="mb-4">
                    {!! $project->description !!}
                </div>

                <!-- Highlights -->
                @if($project->highlights)
                <div class="mb-4">
                    <h3 style="color:var(--secondary);margin-bottom:20px;">Key Highlights</h3>
                    <div class="row g-3">
                        @foreach($project->highlights as $highlight)
                        <div class="col-md-6">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <i class="fas fa-check-circle me-3" style="color:var(--primary);font-size:1.2rem;"></i>
                                <span>{{ $highlight }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Amenities -->
                @if($project->amenities->count() > 0)
                <div class="mb-4">
                    <h3 style="color:var(--secondary);margin-bottom:20px;">Amenities</h3>
                    <div class="row g-3">
                        @foreach($project->amenities as $amenity)
                        <div class="col-md-4 col-6">
                            <div class="amenity-item">
                                <div class="icon-box" style="width:50px;height:50px;">
                                    <i class="fas {{ $amenity->icon ?? 'fa-star' }}"></i>
                                </div>
                                <h5 style="font-size:0.9rem;margin-top:10px;">{{ $amenity->name }}</h5>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Floor Plans -->
                @if($project->floorPlans->count() > 0)
                <div class="mb-4">
                    <h3 style="color:var(--secondary);margin-bottom:20px;">Floor Plans</h3>
                    <div class="row g-3">
                        @foreach($project->floorPlans as $fp)
                        <div class="col-md-6">
                            <div class="card border-0 shadow-sm">
                                @if($fp->image)
                                <img src="{{ upload_url($fp->image) }}" class="card-img-top" alt="{{ $fp->name }}">
                                @endif
                                <div class="card-body">
                                    <h5 style="color:var(--secondary);">{{ $fp->name }}</h5>
                                    <p class="mb-1"><strong>Type:</strong> {{ $fp->bhk_type }}</p>
                                    <p class="mb-1"><strong>Area:</strong> {{ $fp->area_sqft }} sq.ft</p>
                                    @if($fp->price)
                                    <p class="mb-0"><strong>Price:</strong> ₹{{ number_format($fp->price) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Location Map -->
                @if($project->latitude && $project->longitude)
                <div class="mb-4">
                    <h3 style="color:var(--secondary);margin-bottom:20px;">Location</h3>
                    <div style="border-radius:15px;overflow:hidden;">
                        <iframe src="https://maps.google.com/maps?q={{ $project->latitude }},{{ $project->longitude }}&z=15&output=embed" width="100%" height="400" style="border:0;" allowfullscreen></iframe>
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="col-lg-4">
                <!-- Quick Info -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h4 style="color:var(--secondary);margin-bottom:20px;">Project Details</h4>
                        <table class="table table-borderless mb-0">
                            <tr><td class="text-muted">Type</td><td class="fw-bold text-capitalize">{{ $project->type }}</td></tr>
                            <tr><td class="text-muted">Status</td><td><span class="badge bg-{{ $project->status == 'ongoing' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">{{ ucfirst($project->status) }}</span></td></tr>
                            <tr><td class="text-muted">Location</td><td class="fw-bold">{{ $project->location }}</td></tr>
                            <tr><td class="text-muted">Price</td><td class="fw-bold" style="color:var(--primary);">{{ $project->price_range }}</td></tr>
                            @if($project->total_units)<tr><td class="text-muted">Total Units</td><td class="fw-bold">{{ $project->total_units }}</td></tr>@endif
                            @if($project->area_min)<tr><td class="text-muted">Area Range</td><td class="fw-bold">{{ $project->area_range }}</td></tr>@endif
                            @if($project->bhk_options)<tr><td class="text-muted">BHK Options</td><td class="fw-bold">{{ str_replace(',', ', ', $project->bhk_options) }} BHK</td></tr>@endif
                            @if($project->rera_id)<tr><td class="text-muted">RERA ID</td><td class="fw-bold">{{ $project->rera_id }}</td></tr>@endif
                            @if($project->possession_date)<tr><td class="text-muted">Possession</td><td class="fw-bold">{{ $project->possession_date->format('M Y') }}</td></tr>@endif
                        </table>
                    </div>
                </div>

                <!-- Enquiry Form -->
                <div class="enquiry-form">
                    <h3><i class="fas fa-phone-alt me-2" style="color:var(--primary);"></i>Get a Callback</h3>
                    <form action="{{ route('enquiry.submit') }}" method="POST">
                        @csrf
                        <input type="hidden" name="project_id" value="{{ $project->id }}">
                        <input type="hidden" name="type" value="project">
                        <div class="mb-3">
                            <input type="text" name="name" class="form-control" placeholder="Your Name *" required>
                        </div>
                        <div class="mb-3">
                            <input type="tel" name="phone" class="form-control" placeholder="Phone Number *" required>
                        </div>
                        <div class="mb-3">
                            <input type="email" name="email" class="form-control" placeholder="Email Address">
                        </div>
                        <div class="mb-3">
                            <textarea name="message" class="form-control" rows="3" placeholder="Message (Optional)"></textarea>
                        </div>
                        <button type="submit" class="btn-primary-custom border-0 w-100" style="padding:12px;cursor:pointer;">Enquire Now</button>
                    </form>
                </div>

                @if($project->brochure)
                <a href="{{ upload_url($project->brochure) }}" class="btn-primary-custom w-100 text-center d-block mt-3" download><i class="fas fa-download me-2"></i>Download Brochure</a>
                @endif
            </div>
        </div>

        <!-- Similar Projects -->
        @if($similarProjects->count() > 0)
        <div class="mt-5">
            <div class="section-title"><h2>Similar Projects</h2></div>
            <div class="row g-4">
                @foreach($similarProjects as $sp)
                <div class="col-lg-4 col-md-6">
                    <div class="project-card position-relative">
                        <div class="card-img-top d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #1a1a2e, #2d2d5e);">
                            <div class="text-center text-white p-4">
                                <i class="fas fa-building fa-3x mb-3" style="color: var(--gold);"></i>
                                <h5>{{ $sp->name }}</h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <h4 class="card-title">{{ $sp->name }}</h4>
                            <p class="location"><i class="fas fa-map-marker-alt"></i> {{ $sp->location }}</p>
                            <p class="price">{{ $sp->price_range }}</p>
                            <a href="{{ route('project.detail', $sp->slug) }}" class="btn-primary-custom w-100 text-center d-block">View Details</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>
@endsection
