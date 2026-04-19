<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\UploadService;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    protected UploadService $upload;

    public function __construct(UploadService $upload)
    {
        $this->upload = $upload;
    }

    public function index()
    {
        $settings = SiteSetting::orderBy('group')->orderBy('id')->get()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token', '_method') as $key => $value) {
            if ($request->hasFile($key)) {
                $old = SiteSetting::where('key', $key)->first();
                if ($old && $old->value) {
                    $this->upload->delete($old->value);
                }
                $value = $this->upload->upload($request->file($key), 'settings');
            }
            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        cache()->forget('site_settings');
        return redirect()->back()->with('success', 'Settings updated successfully.');
    }
}
