@extends('admin.layout')
@section('title', 'Testimonials')
@section('page-title', 'Manage Testimonials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Testimonials</h4>
    <a href="{{ route('admin.testimonials.create') }}" class="btn btn-sm-primary"><i class="fas fa-plus me-2"></i>Add Testimonial</a>
</div>
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Name</th><th>Designation</th><th>Rating</th><th>Project</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($testimonials as $i => $t)
                <tr>
                    <td>{{ $testimonials->firstItem() + $i }}</td>
                    <td><strong>{{ $t->name }}</strong></td>
                    <td>{{ $t->designation }}{{ $t->company ? ', ' . $t->company : '' }}</td>
                    <td>@for($j=0;$j<$t->rating;$j++)<i class="fas fa-star text-warning"></i>@endfor</td>
                    <td>{{ $t->project->name ?? 'General' }}</td>
                    <td><span class="badge bg-{{ $t->is_active ? 'success' : 'secondary' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.testimonials.edit', $t) }}" class="btn btn-sm btn-sm-primary btn-action"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.testimonials.destroy', $t) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $testimonials->withQueryString()->links() }}
</div>
@endsection
