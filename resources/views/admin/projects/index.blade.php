@extends('admin.layout')
@section('title', 'Projects')
@section('page-title', 'Manage Projects')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">All Projects</h4>
    <a href="{{ route('admin.projects.create') }}" class="btn btn-sm-primary"><i class="fas fa-plus me-2"></i>Add Project</a>
</div>

<div class="data-table">
    <div class="row mb-3">
        <form method="GET" action="{{ route('admin.projects.index') }}" class="row g-2">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control form-control-sm" placeholder="Search projects..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-sm">
                    <option value="">All Status</option>
                    <option value="ongoing" {{ request('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="upcoming" {{ request('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                </select>
            </div>
            <div class="col-md-2"><button type="submit" class="btn btn-sm btn-sm-primary w-100">Filter</button></div>
        </form>
    </div>

    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th><th>Name</th><th>Location</th><th>Type</th><th>Status</th><th>Price Range</th><th>Featured</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($projects as $i => $project)
                <tr>
                    <td>{{ $projects->firstItem() + $i }}</td>
                    <td><strong>{{ $project->name }}</strong></td>
                    <td>{{ $project->location }}</td>
                    <td class="text-capitalize">{{ $project->type }}</td>
                    <td><span class="badge bg-{{ $project->status == 'ongoing' ? 'success' : ($project->status == 'completed' ? 'primary' : 'warning') }}">{{ ucfirst($project->status) }}</span></td>
                    <td>{{ $project->price_range }}</td>
                    <td>{{ $project->is_featured ? '<i class="fas fa-star text-warning"></i>' : '-' }}</td>
                    <td>
                        <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-info text-white btn-action" title="View"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('admin.projects.edit', $project) }}" class="btn btn-sm btn-sm-primary btn-action" title="Edit"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.projects.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this project?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger btn-action" title="Delete"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $projects->withQueryString()->links() }}
</div>
@endsection
