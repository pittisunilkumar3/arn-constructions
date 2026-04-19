<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Enquiry;
use App\Models\Testimonial;
use App\Models\Gallery;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProjects = Project::count();
        $ongoingProjects = Project::where('status', 'ongoing')->count();
        $completedProjects = Project::where('status', 'completed')->count();
        $totalEnquiries = Enquiry::count();
        $newEnquiries = Enquiry::where('status', 'new')->count();
        $recentEnquiries = Enquiry::with('project')->latest()->take(5)->get();
        $totalGalleries = Gallery::count();

        return view('admin.dashboard', compact(
            'totalProjects', 'ongoingProjects', 'completedProjects',
            'totalEnquiries', 'newEnquiries', 'recentEnquiries', 'totalGalleries'
        ));
    }
}
