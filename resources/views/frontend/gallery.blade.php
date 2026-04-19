@extends('frontend.layout')
@section('title', 'Gallery')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Our <span style="color:var(--gold)">Gallery</span></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Gallery</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="row mb-4">
            <form method="GET" action="{{ route('gallery') }}" class="row g-3">
                <div class="col-md-4">
                    <select name="category" class="form-control">
                        <option value="">All Categories</option>
                        <option value="interior" {{ request('category') == 'interior' ? 'selected' : '' }}>Interior</option>
                        <option value="exterior" {{ request('category') == 'exterior' ? 'selected' : '' }}>Exterior</option>
                        <option value="amenities" {{ request('category') == 'amenities' ? 'selected' : '' }}>Amenities</option>
                        <option value="site" {{ request('category') == 'site' ? 'selected' : '' }}>Site Photos</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <select name="project_id" class="form-control">
                        <option value="">All Projects</option>
                        @foreach($projects as $p)
                        <option value="{{ $p->id }}" {{ request('project_id') == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn-primary-custom border-0 w-100" style="padding:10px;cursor:pointer;"><i class="fas fa-search me-2"></i>Filter</button>
                </div>
            </form>
        </div>

        <div class="row g-3">
            @foreach($galleries as $item)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="position-relative overflow-hidden rounded" style="height: 250px;">
                    <img src="{{ upload_url($item->image) }}" class="w-100 h-100" style="object-fit:cover;" alt="{{ $item->title }}">
                    <div class="position-absolute bottom-0 start-0 end-0 p-3" style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                        <small class="text-white">{{ $item->title ?? ucfirst($item->category) }}</small>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($galleries->isEmpty())
        <div class="text-center py-5">
            <i class="fas fa-images fa-4x mb-3" style="color:#ddd;"></i>
            <h4 class="text-muted">Gallery Coming Soon</h4>
            <p class="text-muted">We're updating our gallery. Check back soon!</p>
        </div>
        @endif

        <div class="mt-4">{{ $galleries->withQueryString()->links() }}</div>
    </div>
</section>
@endsection
