<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Testimonial;
use App\Models\HomeSlider;
use App\Models\SiteSetting;
use App\Models\Amenity;
use App\Models\Gallery;
use App\Models\Enquiry;
use App\Mail\EnquiryMail;
use App\Mail\ContactAutoReply;
use App\Services\SmtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class HomeController extends Controller
{
    public function __construct()
    {
        // Share settings and footer projects with ALL frontend views
        View::share('settings', SiteSetting::getAllSettings());
        View::share('footerProjects', Project::active()->latest()->take(5)->get(['id', 'name', 'slug']));
    }

    public function index()
    {
        $sliders = HomeSlider::active()->get();
        $featuredProjects = Project::active()->featured()->latest()->take(6)->get();
        $ongoingProjects = Project::active()->where('status', 'ongoing')->latest()->take(3)->get();
        $completedProjects = Project::active()->where('status', 'completed')->latest()->take(3)->get();
        $testimonials = Testimonial::active()->take(6)->get();
        $enquiryProjects = Project::active()->get(['id', 'name']);

        return view('frontend.index', compact(
            'sliders', 'featuredProjects', 'ongoingProjects',
            'completedProjects', 'testimonials', 'enquiryProjects'
        ));
    }

    public function about()
    {
        return view('frontend.about');
    }

    public function projects(Request $request)
    {
        $query = Project::active();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('bhk')) {
            $query->where('bhk_options', 'like', '%' . $request->bhk . '%');
        }
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        $projects = $query->latest()->paginate(12);
        return view('frontend.projects', compact('projects'));
    }

    public function projectDetail($slug)
    {
        $project = Project::active()->where('slug', $slug)->firstOrFail();
        $project->load(['amenities', 'floorPlans', 'galleries', 'testimonials']);
        $similarProjects = Project::active()
            ->where('id', '!=', $project->id)
            ->where('type', $project->type)
            ->take(3)->get();

        return view('frontend.project-detail', compact('project', 'similarProjects'));
    }

    public function gallery(Request $request)
    {
        $query = Gallery::where('is_active', true);

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $galleries = $query->orderBy('sort_order')->paginate(24);
        $projects = Project::active()->get();
        return view('frontend.gallery', compact('galleries', 'projects'));
    }

    public function contact()
    {
        return view('frontend.contact');
    }

    public function submitEnquiry(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'type' => 'nullable|in:general,project,callback',
        ]);

        $enquiry = Enquiry::create($validated);
        $this->sendAdminNotification($enquiry);

        return redirect()->back()->with('success', 'Thank you for your enquiry! Our team will contact you shortly.');
    }

    public function submitContact(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'required|string|max:20',
            'subject' => 'nullable|string|max:255',
            'message' => 'nullable|string',
            'project_id' => 'nullable|exists:projects,id',
            'type' => 'nullable|in:general,project,callback,contact',
        ]);

        $validated['type'] = 'contact';
        $validated['source'] = 'contact_form';
        $enquiry = Enquiry::create($validated);

        // Send notification to admin
        $this->sendAdminNotification($enquiry);

        // Send auto-reply to customer
        if (!empty($validated['email']) && SmtpService::configure()) {
            try {
                SmtpService::send(
                    $validated['email'],
                    'Thank you for contacting ' . SiteSetting::get('site_name', 'ARN Constructions'),
                    (new ContactAutoReply($validated['name']))->render()
                );
            } catch (\Exception $e) {
                \Log::error('Contact auto-reply failed: ' . $e->getMessage());
            }
        }

        return redirect()->back()->with('success', 'Thank you for contacting us! We have received your message and will get back to you within 24 hours.');
    }

    /**
     * Send enquiry notification to admin emails.
     */
    private function sendAdminNotification(Enquiry $enquiry): void
    {
        if (!SmtpService::configure()) {
            return;
        }

        try {
            $adminEmails = collect([
                SiteSetting::get('email_primary'),
                SiteSetting::get('email_sales'),
            ])->filter()->unique();

            foreach ($adminEmails as $adminEmail) {
                SmtpService::send(
                    $adminEmail,
                    'New Enquiry: ' . ($enquiry->subject ?: $enquiry->name),
                    (new EnquiryMail($enquiry))->render()
                );
            }
        } catch (\Exception $e) {
            \Log::error('Admin notification email failed: ' . $e->getMessage());
        }
    }

    public function amenities()
    {
        $amenities = Amenity::where('is_active', true)->orderBy('sort_order')->get();
        return view('frontend.amenities', compact('amenities'));
    }
}
