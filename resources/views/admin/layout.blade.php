<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - ARN Constructions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --sidebar-width: 260px; --primary: #b8860b; --secondary: #1a1a2e; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f4f6f9; }

        /* Sidebar */
        .sidebar { position: fixed; top: 0; left: 0; width: var(--sidebar-width); height: 100vh; background: linear-gradient(180deg, var(--secondary) 0%, #16213e 100%); color: #fff; z-index: 1000; overflow-y: auto; transition: all 0.3s; }
        .sidebar-brand { padding: 20px; text-align: center; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .sidebar-brand h4 { margin: 0; font-family: 'Playfair Display', serif; }
        .sidebar-brand h4 span { color: var(--primary); }
        .sidebar-brand small { color: #888; font-size: 12px; }

        .sidebar-menu { padding: 15px 0; }
        .sidebar-menu .menu-label { padding: 10px 20px; font-size: 11px; text-transform: uppercase; color: #666; letter-spacing: 1px; font-weight: 600; }
        .sidebar-menu a { display: flex; align-items: center; padding: 12px 20px; color: #bbb; text-decoration: none; transition: all 0.3s; font-size: 14px; border-left: 3px solid transparent; }
        .sidebar-menu a:hover, .sidebar-menu a.active { background: rgba(184,134,11,0.1); color: var(--primary); border-left-color: var(--primary); }
        .sidebar-menu a i { width: 20px; margin-right: 12px; text-align: center; }
        .sidebar-menu a .badge { margin-left: auto; font-size: 10px; }

        /* Main Content */
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; }

        /* Top Navbar */
        .top-nav { background: #fff; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.05); position: sticky; top: 0; z-index: 100; }
        .top-nav .page-title h5 { margin: 0; font-weight: 600; color: var(--secondary); }

        .content-area { padding: 30px; }

        /* Cards */
        .stat-card { background: #fff; border-radius: 12px; padding: 25px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); transition: all 0.3s; border-left: 4px solid; }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(0,0,0,0.1); }
        .stat-card .icon { width: 50px; height: 50px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: #fff; }
        .stat-card h3 { font-size: 1.8rem; font-weight: 700; margin: 10px 0 5px; }
        .stat-card p { color: #888; margin: 0; font-size: 14px; }

        /* Tables */
        .data-table { background: #fff; border-radius: 12px; padding: 20px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .data-table table { margin: 0; }
        .data-table th { font-weight: 600; color: var(--secondary); border-bottom-width: 1px; font-size: 13px; text-transform: uppercase; }
        .data-table td { font-size: 14px; vertical-align: middle; }

        /* Action Buttons */
        .btn-action { padding: 5px 10px; font-size: 12px; border-radius: 5px; }
        .btn-sm-primary { background: var(--primary); color: #fff; border: none; }
        .btn-sm-primary:hover { background: var(--secondary); color: #fff; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .sidebar.show { margin-left: 0; }
            .main-content { margin-left: 0; }
        }

        /* Form Styles */
        .form-card { background: #fff; border-radius: 12px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .form-card .form-label { font-weight: 500; color: var(--secondary); font-size: 14px; }
        .form-card .form-control, .form-card .form-select { border-radius: 8px; padding: 10px 15px; border-color: #e0e0e0; }
        .form-card .form-control:focus, .form-card .form-select:focus { border-color: var(--primary); box-shadow: 0 0 0 0.2rem rgba(184,134,11,0.15); }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo.jpeg') }}" alt="ARN Constructions" style="height: 45px; border-radius: 50%; margin-bottom: 8px;"><br>
            <h4><span>ARN</span> Admin</h4>
            <small>Super Admin Panel</small>
        </div>
        <div class="sidebar-menu">
            <div class="menu-label">Main</div>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>

            <div class="menu-label">Manage Content</div>
            <a href="{{ route('admin.projects.index') }}" class="{{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
                <i class="fas fa-building"></i> Projects
            </a>
            <a href="{{ route('admin.floor-plans.index') }}" class="{{ request()->routeIs('admin.floor-plans.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i> Floor Plans
            </a>
            <a href="{{ route('admin.amenities.index') }}" class="{{ request()->routeIs('admin.amenities.*') ? 'active' : '' }}">
                <i class="fas fa-swimming-pool"></i> Amenities
            </a>
            <a href="{{ route('admin.gallery.index') }}" class="{{ request()->routeIs('admin.gallery.*') ? 'active' : '' }}">
                <i class="fas fa-images"></i> Gallery
            </a>
            <a href="{{ route('admin.sliders.index') }}" class="{{ request()->routeIs('admin.sliders.*') ? 'active' : '' }}">
                <i class="fas fa-photo-video"></i> Home Sliders
            </a>
            <a href="{{ route('admin.testimonials.index') }}" class="{{ request()->routeIs('admin.testimonials.*') ? 'active' : '' }}">
                <i class="fas fa-quote-left"></i> Testimonials
            </a>

            <div class="menu-label">Leads</div>
            <a href="{{ route('admin.enquiries.index') }}" class="{{ request()->routeIs('admin.enquiries.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> Enquiries
                @php $newCount = \App\Models\Enquiry::where('status', 'new')->count(); @endphp
                @if($newCount > 0)<span class="badge bg-danger">{{ $newCount }}</span>@endif
            </a>

            <div class="menu-label">Settings</div>
            <a href="{{ route('admin.settings.index') }}" class="{{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                <i class="fas fa-cog"></i> Site Settings
            </a>
            <a href="{{ route('admin.smtp.index') }}" class="{{ request()->routeIs('admin.smtp.*') ? 'active' : '' }}">
                <i class="fas fa-envelope"></i> SMTP Settings
            </a>

            <div class="menu-label mt-3"></div>
            <a href="{{ route('home') }}" target="_blank">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
            <a href="{{ route('admin.logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="top-nav">
            <div class="page-title">
                <h5>@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="text-muted" style="font-size:14px;">{{ Auth::user()->name }}</span>
                <div style="width:40px;height:40px;border-radius:50%;background:linear-gradient(135deg,var(--primary),#d4a843);display:flex;align-items:center;justify-content:center;color:#fff;font-weight:600;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            </div>
        </div>

        <div class="content-area">
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif
            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
