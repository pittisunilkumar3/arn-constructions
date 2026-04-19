@extends('admin.layout')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="border-left-color: #6777ef;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $totalProjects }}</h3>
                    <p>Total Projects</p>
                </div>
                <div class="icon" style="background: #6777ef;"><i class="fas fa-building"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="border-left-color: #28a745;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $ongoingProjects }}</h3>
                    <p>Ongoing Projects</p>
                </div>
                <div class="icon" style="background: #28a745;"><i class="fas fa-hard-hat"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="border-left-color: #ffc107;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $totalEnquiries }}</h3>
                    <p>Total Enquiries</p>
                </div>
                <div class="icon" style="background: #ffc107;"><i class="fas fa-envelope"></i></div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-card" style="border-left-color: #dc3545;">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3>{{ $newEnquiries }}</h3>
                    <p>New Enquiries</p>
                </div>
                <div class="icon" style="background: #dc3545;"><i class="fas fa-bell"></i></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="data-table">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Recent Enquiries</h5>
                <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-sm-primary">View All</a>
            </div>
            <table class="table table-hover">
                <thead>
                    <tr><th>Name</th><th>Phone</th><th>Project</th><th>Status</th><th>Date</th></tr>
                </thead>
                <tbody>
                    @foreach($recentEnquiries as $enquiry)
                    <tr>
                        <td>{{ $enquiry->name }}</td>
                        <td>{{ $enquiry->phone }}</td>
                        <td>{{ $enquiry->project->name ?? 'General' }}</td>
                        <td><span class="badge bg-{{ $enquiry->status == 'new' ? 'danger' : ($enquiry->status == 'contacted' ? 'warning' : 'success') }}">{{ ucfirst($enquiry->status) }}</span></td>
                        <td>{{ $enquiry->created_at->format('d M Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="data-table">
            <h5 class="mb-3">Quick Stats</h5>
            <div class="mb-3 p-3 bg-light rounded">
                <div class="d-flex justify-content-between"><span>Completed Projects</span><strong>{{ $completedProjects }}</strong></div>
            </div>
            <div class="mb-3 p-3 bg-light rounded">
                <div class="d-flex justify-content-between"><span>Total Gallery Images</span><strong>{{ $totalGalleries }}</strong></div>
            </div>
            <div class="mb-3 p-3 bg-light rounded">
                <div class="d-flex justify-content-between"><span>Testimonials</span><strong>{{ \App\Models\Testimonial::count() }}</strong></div>
            </div>
            <div class="p-3 bg-light rounded">
                <div class="d-flex justify-content-between"><span>Amenities</span><strong>{{ \App\Models\Amenity::count() }}</strong></div>
            </div>
        </div>
    </div>
</div>
@endsection
