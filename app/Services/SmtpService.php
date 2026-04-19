<?php

namespace App\Services;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class SmtpService
{
    /**
     * Configure SMTP on the fly from database settings.
     */
    public static function configure(): bool
    {
        $settings = SiteSetting::getAllSettings();

        if (empty($settings['smtp_enabled']) || $settings['smtp_enabled'] !== '1') {
            return false;
        }

        if (empty($settings['smtp_host']) || empty($settings['smtp_username'])) {
            return false;
        }

        Config::set('mail.default', 'smtp');
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $settings['smtp_host'],
            'port' => (int) ($settings['smtp_port'] ?? 587),
            'encryption' => $settings['smtp_encryption'] ?? 'tls',
            'username' => $settings['smtp_username'],
            'password' => $settings['smtp_password'],
            'timeout' => 30,
            'local_domain' => env('MAIL_EHLO_DOMAIN'),
        ]);

        Config::set('mail.from', [
            'address' => $settings['mail_from_address'] ?: $settings['smtp_username'],
            'name' => $settings['mail_from_name'] ?? config('app.name'),
        ]);

        return true;
    }

    /**
     * Send an email using dynamically configured SMTP.
     */
    public static function send(string $to, string $subject, string $body, ?string $toName = null): bool
    {
        if (!self::configure()) {
            \Log::info('SMTP not configured. Email not sent.');
            return false;
        }

        try {
            Mail::html($body, function ($message) use ($to, $subject, $toName) {
                $message->to($to, $toName)
                        ->subject($subject);
            });
            return true;
        } catch (\Exception $e) {
            \Log::error('SMTP Send Error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Test SMTP connection by sending a test email.
     */
    public static function testConnection(string $testEmail): array
    {
        if (!self::configure()) {
            return ['success' => false, 'message' => 'SMTP is not enabled or not configured.'];
        }

        try {
            Mail::html(
                '<h2>SMTP Test - ARN Constructions</h2>
                 <p>This is a test email to verify your SMTP configuration.</p>
                 <p><strong>Sent at:</strong> ' . now()->format('d M Y, h:i A') . '</p>
                 <hr>
                 <p style="color:#888;font-size:12px;">If you received this email, your SMTP settings are working correctly.</p>',
                function ($message) use ($testEmail) {
                    $message->to($testEmail)
                            ->subject('SMTP Test Email - ARN Constructions');
                }
            );
            return ['success' => true, 'message' => 'Test email sent successfully to ' . $testEmail];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed: ' . $e->getMessage()];
        }
    }
}
