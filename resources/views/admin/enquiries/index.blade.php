@extends('admin.layout')
@section('title', 'Enquiries')
@section('page-title', 'Manage Enquiries')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Enquiries</h4>
</div>

<div class="data-table">
    <div class="row mb-3">
        <form method="GET" action="{{ route('admin.enquiries.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search by name/phone..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="new" {{ request('status') == 'new' ? 'selected' : '' }}>New</option>
                    <option value="contacted" {{ request('status') == 'contacted' ? 'selected' : '' }}>Contacted</option>
                    <option value="qualified" {{ request('status') == 'qualified' ? 'selected' : '' }}>Qualified</option>
                    <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                    <option value="lost" {{ request('status') == 'lost' ? 'selected' : '' }}>Lost</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-sm btn-sm-primary w-100">Filter</button></div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>#</th><th>Name</th><th>Phone</th><th>Email</th><th>Project</th><th>Type</th><th>Status</th><th>Date</th><th>Actions</th></tr>
            </thead>
            <tbody>
                @foreach($enquiries as $i => $enquiry)
                <tr>
                    <td>{{ $enquiries->firstItem() + $i }}</td>
                    <td><strong>{{ $enquiry->name }}</strong></td>
                    <td><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a></td>
                    <td>{{ $enquiry->email ?: '-' }}</td>
                    <td>{{ $enquiry->project->name ?? 'General' }}</td>
                    <td class="text-capitalize">{{ $enquiry->type }}</td>
                    <td>
                        <span class="badge bg-{{ $enquiry->status == 'new' ? 'danger' : ($enquiry->status == 'contacted' ? 'warning' : ($enquiry->status == 'converted' ? 'success' : 'secondary')) }}">
                            {{ ucfirst($enquiry->status) }}
                        </span>
                    </td>
                    <td>{{ $enquiry->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.enquiries.show', $enquiry) }}" class="btn btn-sm btn-info text-white btn-action"><i class="fas fa-eye"></i></a>
                        <form action="{{ route('admin.enquiries.destroy', $enquiry) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this enquiry?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $enquiries->withQueryString()->links() }}
</div>
@endsection
