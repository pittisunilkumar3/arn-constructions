@extends('admin.layout')
@section('title', 'Home Sliders')
@section('page-title', 'Manage Home Sliders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Home Page Sliders</h4>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-sm-primary"><i class="fas fa-plus me-2"></i>Add Slider</a>
</div>
<div class="data-table">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>#</th><th>Image</th><th>Title</th><th>Subtitle</th><th>Button</th><th>Status</th><th>Actions</th></tr></thead>
            <tbody>
                @foreach($sliders as $i => $s)
                <tr>
                    <td>{{ $sliders->firstItem() + $i }}</td>
                    <td><img src="{{ upload_url($s->image) }}" style="height:50px;width:100px;object-fit:cover;" class="rounded"></td>
                    <td>{{ $s->title }}</td>
                    <td>{{ $s->subtitle }}</td>
                    <td>{{ $s->button_text ?: '-' }}</td>
                    <td><span class="badge bg-{{ $s->is_active ? 'success' : 'secondary' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <a href="{{ route('admin.sliders.edit', $s) }}" class="btn btn-sm btn-sm-primary btn-action"><i class="fas fa-edit"></i></a>
                        <form action="{{ route('admin.sliders.destroy', $s) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete?')">
                            @csrf @method('DELETE')<button class="btn btn-sm btn-danger btn-action"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    {{ $sliders->withQueryString()->links() }}
</div>
@endsection
