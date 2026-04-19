@extends('admin.layout')
@section('title', 'Site Settings')
@section('page-title', 'Site Settings')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')

    @foreach($settings as $groupName => $groupSettings)
    <div class="form-card mb-4">
        <h5 class="mb-3 text-capitalize">{{ $groupName }} Settings</h5>
        <div class="row g-3">
            @foreach($groupSettings as $setting)
            <div class="col-md-6">
                <label class="form-label">{{ $setting->label ?? $setting->key }}</label>
                @if($setting->type == 'textarea')
                    <textarea name="{{ $setting->key }}" class="form-control" rows="3">{{ old($setting->key, $setting->value) }}</textarea>
                @elseif($setting->type == 'image' || $setting->type == 'file')
                    @if($setting->value)<div class="mb-2">@if(str_ends_with($setting->value, '.jpg') || str_ends_with($setting->value, '.png'))<img src="{{ upload_url($setting->value) }}" style="max-height:80px;" class="rounded">@else<a href="{{ upload_url($setting->value) }}" target="_blank">View File</a>@endif</div>@endif
                    <input type="file" name="{{ $setting->key }}" class="form-control">
                @else
                    <input type="text" name="{{ $setting->key }}" class="form-control" value="{{ old($setting->key, $setting->value) }}">
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @endforeach

    <button type="submit" class="btn btn-sm-primary px-4"><i class="fas fa-save me-2"></i>Save All Settings</button>
</form>
@endsection
