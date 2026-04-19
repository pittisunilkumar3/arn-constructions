@extends('admin.layout')
@section('title', 'Enquiry Details')
@section('page-title', 'Enquiry from ' . $enquiry->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Enquiry from {{ $enquiry->name }}</h4>
    <a href="{{ route('admin.enquiries.index') }}" class="btn btn-sm btn-secondary"><i class="fas fa-arrow-left me-2"></i>Back</a>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="form-card">
            <h5 class="mb-3">Enquiry Details</h5>
            <table class="table">
                <tr><td class="fw-bold" width="150">Name</td><td>{{ $enquiry->name }}</td></tr>
                <tr><td class="fw-bold">Phone</td><td><a href="tel:{{ $enquiry->phone }}">{{ $enquiry->phone }}</a> | <a href="https://wa.me/91{{ $enquiry->phone }}" target="_blank" class="text-success"><i class="fab fa-whatsapp"></i> WhatsApp</a></td></tr>
                <tr><td class="fw-bold">Email</td><td>{{ $enquiry->email ? '<a href="mailto:'.$enquiry->email.'">'.$enquiry->email.'</a>' : 'N/A' }}</td></tr>
                <tr><td class="fw-bold">Project</td><td>{{ $enquiry->project->name ?? 'General Enquiry' }}</td></tr>
                <tr><td class="fw-bold">Type</td><td class="text-capitalize">{{ $enquiry->type }}</td></tr>
                <tr><td class="fw-bold">Source</td><td class="text-capitalize">{{ $enquiry->source }}</td></tr>
                <tr><td class="fw-bold">Subject</td><td>{{ $enquiry->subject ?: 'N/A' }}</td></tr>
                <tr><td class="fw-bold">Message</td><td>{{ $enquiry->message ?: 'N/A' }}</td></tr>
                <tr><td class="fw-bold">Received</td><td>{{ $enquiry->created_at->format('d M Y, h:i A') }}</td></tr>
            </table>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-card">
            <h5 class="mb-3">Update Status</h5>
            <form action="{{ route('admin.enquiries.update-status', $enquiry) }}" method="POST">
                @csrf @method('PUT')
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        @foreach(['new','contacted','qualified','converted','lost'] as $s)
                        <option value="{{ $s }}" {{ $enquiry->status == $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Notes</label>
                    <textarea name="notes" class="form-control" rows="4">{{ old('notes', $enquiry->notes) }}</textarea>
                </div>
                <button type="submit" class="btn btn-sm-primary w-100"><i class="fas fa-save me-2"></i>Update</button>
            </form>
        </div>
    </div>
</div>
@endsection
