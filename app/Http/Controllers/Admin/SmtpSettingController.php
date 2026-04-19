<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use App\Services\SmtpService;
use Illuminate\Http\Request;

class SmtpSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getAllSettings();
        return view('admin.settings.smtp', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'smtp_host' => 'nullable|string|max:255',
            'smtp_port' => 'nullable|integer|min:1|max:65535',
            'smtp_username' => 'nullable|string|max:255',
            'smtp_password' => 'nullable|string|max:255',
            'smtp_encryption' => 'nullable|string|in:tls,ssl,',
            'mail_from_address' => 'nullable|email|max:255',
            'mail_from_name' => 'nullable|string|max:255',
            'smtp_enabled' => 'nullable|in:0,1',
        ]);

        // Build map of fields and their types
        $fields = [
            'smtp_host' => 'text',
            'smtp_port' => 'text',
            'smtp_username' => 'text',
            'smtp_password' => 'password',
            'smtp_encryption' => 'text',
            'mail_from_address' => 'text',
            'mail_from_name' => 'text',
            'smtp_enabled' => 'text',
        ];

        foreach ($fields as $key => $type) {
            $value = $request->input($key, '');

            // Don't overwrite password if left blank
            if ($key === 'smtp_password' && empty($value)) {
                $existing = SiteSetting::where('key', $key)->first();
                if ($existing && $existing->value) {
                    continue;
                }
            }

            SiteSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => 'smtp', 'label' => ucwords(str_replace('_', ' ', $key))]
            );
        }

        cache()->forget('site_settings');

        return redirect()->route('admin.smtp.index')->with('success', 'SMTP settings saved successfully.');
    }

    public function testEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Save settings first if they're new, so test uses latest
        cache()->forget('site_settings');

        $result = SmtpService::testConnection($request->email);

        return response()->json($result);
    }
}
