<?php

namespace App\Mail;

use App\Models\SiteSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReply extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $customerName) {}

    public function envelope(): Envelope
    {
        $siteName = SiteSetting::get('site_name', 'ARN Constructions');
        return new Envelope(
            subject: "Thank you for contacting {$siteName}",
        );
    }

    public function content(): Content
    {
        $siteName = SiteSetting::get('site_name', 'ARN Constructions');
        $phone = SiteSetting::get('phone_primary', '');
        $email = SiteSetting::get('email_primary', '');
        $whatsapp = SiteSetting::get('whatsapp', '');
        $name = $this->customerName;

        return new Content(
            htmlString: <<<HTML
            <div style="font-family: 'Poppins', Arial, sans-serif; max-width: 600px; margin: 0 auto; background: #f9f9f9; border-radius: 12px; overflow: hidden;">
                <div style="background: linear-gradient(135deg, #1a1a2e, #0f0f23); padding: 30px; text-align: center;">
                    <h1 style="color: #d4a843; margin: 0; font-size: 22px;">Thank You, {$name}!</h1>
                    <p style="color: #ccc; margin: 8px 0 0; font-size: 14px;">We've received your message</p>
                </div>
                <div style="padding: 30px; background: #fff;">
                    <p style="font-size: 15px; color: #333; line-height: 1.8;">
                        Dear <strong>{$name}</strong>,
                    </p>
                    <p style="font-size: 15px; color: #555; line-height: 1.8;">
                        Thank you for reaching out to <strong>{$siteName}</strong>. We have received your enquiry and our team will get back to you within <strong>24 hours</strong>.
                    </p>
                    <div style="margin: 25px 0; padding: 20px; background: #f4f6f9; border-radius: 8px; border-left: 4px solid #b8860b;">
                        <p style="margin: 0 0 10px; font-weight: 600; color: #1a1a2e; font-size: 14px;">Need immediate assistance?</p>
                        <p style="margin: 0 0 5px; color: #555; font-size: 14px;">
                            📞 Phone: <a href="tel:{$phone}" style="color: #b8860b; text-decoration: none;">{$phone}</a>
                        </p>
                        <p style="margin: 0 0 5px; color: #555; font-size: 14px;">
                            ✉️ Email: <a href="mailto:{$email}" style="color: #b8860b; text-decoration: none;">{$email}</a>
                        </p>
                        @if($whatsapp)
                        <p style="margin: 0; color: #555; font-size: 14px;">
                            💬 WhatsApp: <a href="https://wa.me/{$whatsapp}" style="color: #b8860b; text-decoration: none;">Chat with us</a>
                        </p>
                        @endif
                    </div>
                    <p style="font-size: 14px; color: #888; line-height: 1.7;">
                        In the meantime, feel free to explore our ongoing projects on our website. We look forward to helping you find your dream property!
                    </p>
                </div>
                <div style="padding: 20px; background: #f4f6f9; text-align: center; font-size: 12px; color: #888;">
                    <p style="margin: 0;"><strong>{$siteName}</strong> — Building Dreams, Delivering Excellence</p>
                    <p style="margin: 5px 0 0;">This is an automated response. Please do not reply to this email.</p>
                </div>
            </div>
            HTML,
        );
    }
}
