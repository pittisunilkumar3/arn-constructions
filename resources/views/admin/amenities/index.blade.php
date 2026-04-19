@extends('admin.layout')
@section('title', 'Amenities')
@section('page-title', 'Manage Amenities')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Amenities</h4>
    <a href="{{ route('admin.amenities.create') }}" class="btn btn-sm-primary"><i class="fas fa-plus me-2"></i>Add Amenity</a>
</div>
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Name</th><th>Icon</th><th>Project</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($amenities as $i => $a)
                <tr>
                    <td>{{ $amenities->firstItem() + $i }}</td>
                    <td><strong>{{ $a->name }}</strong></td>
                    <td><i class="fas {{ $a->icon }}"></i> {{ $a->icon }}</td>
                    <td>{{ $a->project->name ?? 'All Projects' }}</td>
                    <td><span class="badge bg-{{ $a->is_active ? 'success' : 'secondary' }}">{{ $a->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.amenities.edit', $a) }}" class="btn btn-sm btn-sm-primary btn-action"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.amenities.destroy', $a) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $amenities->withQueryString()->links() }}
</div>
@endsection
