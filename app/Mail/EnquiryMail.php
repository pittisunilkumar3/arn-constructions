<?php

namespace App\Mail;

use App\Models\Enquiry;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class EnquiryMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Enquiry $enquiry) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Enquiry - ' . ($this->enquiry->subject ?: 'ARN Constructions'),
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: $this->buildHtmlBody(),
        );
    }

    private function buildHtmlBody(): string
    {
        $e = $this->enquiry;
        $project = $e->project?->name ?? 'N/A';
        $email = $e->email ?: 'N/A';
        $subject = $e->subject ?: 'General Enquiry';
        $type = $e->type ?: 'General';
        $message = $e->message ?: 'No message provided.';
        $date = $e->created_at->format('d M Y, h:i A');
        $url = url('/admin/enquiries');

        return <<<HTML
        <div style="font-family: 'Poppins', Arial, sans-serif; max-width: 650px; margin: 0 auto; background: #f9f9f9; border-radius: 12px; overflow: hidden;">
            <div style="background: linear-gradient(135deg, #1a1a2e, #0f0f23); padding: 30px; text-align: center;">
                <h1 style="color: #d4a843; margin: 0; font-size: 24px;">New Enquiry Received</h1>
                <p style="color: #ccc; margin: 8px 0 0; font-size: 14px;">ARN Constructions</p>
            </div>
            <div style="padding: 30px; background: #fff;">
                <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; width: 35%; border-bottom: 1px solid #eee;">Name</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">{$e->name}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; border-bottom: 1px solid #eee;">Phone</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                            <a href="tel:{$e->phone}" style="color: #b8860b;">{$e->phone}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; border-bottom: 1px solid #eee;">Email</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">
                            <a href="mailto:{$e->email}" style="color: #b8860b;">{$email}</a>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; border-bottom: 1px solid #eee;">Subject</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">{$subject}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; border-bottom: 1px solid #eee;">Project</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">{$project}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; border-bottom: 1px solid #eee;">Type</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee; text-transform: capitalize;">{$type}</td>
                    </tr>
                    <tr>
                        <td style="padding: 12px 15px; background: #f4f6f9; font-weight: 600; border-bottom: 1px solid #eee;">Date</td>
                        <td style="padding: 12px 15px; border-bottom: 1px solid #eee;">{$date}</td>
                    </tr>
                </table>
                <div style="margin-top: 20px; padding: 20px; background: #f4f6f9; border-radius: 8px; border-left: 4px solid #b8860b;">
                    <p style="margin: 0 0 5px; font-weight: 600; color: #1a1a2e;">Message:</p>
                    <p style="margin: 0; color: #555; line-height: 1.7;">{$message}</p>
                </div>
            </div>
            <div style="padding: 20px; background: #f4f6f9; text-align: center; font-size: 12px; color: #888;">
                <p style="margin: 0;">This enquiry was submitted from the ARN Constructions website.</p>
                <p style="margin: 5px 0 0;">
                    <a href="{$url}" style="color: #b8860b; text-decoration: none;">View in Admin Panel →</a>
                </p>
            </div>
        </div>
        HTML;
    }
}
