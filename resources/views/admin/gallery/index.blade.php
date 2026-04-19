@extends('admin.layout')
@section('title', 'Gallery')
@section('page-title', 'Manage Gallery')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Gallery Images</h4>
    <a href="{{ route('admin.gallery.create') }}" class="btn btn-sm-primary"><i class="fas fa-plus me-2"></i>Add Image</a>
</div>
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Image</th><th>Title</th><th>Category</th><th>Project</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($galleries as $i => $g)
                <tr>
                    <td>{{ $galleries->firstItem() + $i }}</td>
                    <td><img src="{{ Storage::url($g->image) }}" style="height:60px;width:80px;object-fit:cover;" class="rounded"></td>
                    <td>{{ $g->title ?: '-' }}</td>
                    <td class="text-capitalize">{{ $g->category }}</td>
                    <td>{{ $g->project->name ?? 'All' }}</td>
                    <td><span class="badge bg-{{ $g->is_active ? 'success' : 'secondary' }}">{{ $g->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.gallery.edit', $g) }}" class="btn btn-sm btn-sm-primary btn-action"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.gallery.destroy', $g) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $galleries->withQueryString()->links() }}
</div>
@endsection
