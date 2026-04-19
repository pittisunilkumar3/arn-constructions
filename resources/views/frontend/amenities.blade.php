@extends('frontend.layout')
@section('title', 'Amenities')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Our <span style="color:var(--gold)">Amenities</span></h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item active">Amenities</li>
            </ol>
        </nav>
    </div>
</div>

<section class="section-padding">
    <div class="container">
        <div class="section-title">
            <h2>World-Class Amenities</h2>
            <p>Every ARN project comes with a comprehensive range of amenities designed for modern living</p>
        </div>
        <div class="row g-4">
            @foreach($amenities as $amenity)
            <div class="col-lg-3 col-md-4 col-6">
                <div class="amenity-item">
                    <div class="icon-box">
                        <i class="fas {{ $amenity->icon ?? 'fa-star' }}"></i>
                    </div>
                    <h5>{{ $amenity->name }}</h5>
                    @if($amenity->description)
                    <small class="text-muted">{{ $amenity->description }}</small>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endsection
