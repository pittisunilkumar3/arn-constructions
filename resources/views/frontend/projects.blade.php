@extends('frontend.layout')
@section('title', 'Projects')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Our <span style="color:var(--gold)">Projects</span></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Projects</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <!-- Filters -->
        <div class="row mb-4">
            <form method="GET" action="{{ route('projects') }}" class="row g-3">
                <div class="col-md-3">
                    <select name="type" class="form-control">
                        <option value="">All Types</option>
                        <option value="apartment" {{ request('type') == 'apartment' ? 'selected' : '' }}>Apartments</option>
                        <option value="villa" {{ request('type') == 'villa' ? 'selected' : '' }}>Villas</option>
                        <option value="plot" {{ request('type') == 'plot' ? 'selected' : '' }}>Plots</option>
                        <option value="commercial" {{ request('type') == 'commercial' ? 'selected' : '' }}>Commercial</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="bhk" class="form-control">
                        <option value="">All BHK</option>
                        <option value="1" {{ request('bhk') == '1' ? 'selected' : '' }}>1 BHK</option>
                        <option value="2" {{ request('bhk') == '2' ? 'selected' : '' }}>2 BHK</option>
                        <option value="3" {{ request('bhk') == '3' ? 'selected' : '' }}>3 BHK</option>
                        <option value="4" {{ request('bhk') == '4' ? 'selected' : '' }}>4 BHK</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn-primary-custom border-0 w-100" style="padding:12px;cursor:pointer;"><i class="fas fa-search me-2"></i>Filter</button>
                </div>
            </form>
        </div>

        <div class="row g-4">
            @foreach($projects as $project)
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

        <div class="mt-4">{{ $projects->withQueryString()->links() }}</div>
    </div>
</section>
@endsection
