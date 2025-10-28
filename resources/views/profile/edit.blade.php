<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Edit Profile</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">

    <!-- Tailwind + Breeze Assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        .nav-btn {
            border-radius: 0.5rem;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: background-color 0.2s ease, color 0.2s ease;
        }
        .nav-btn.active {
            background-color: #2e3238;
            color: white !important;
        }
        .nav-btn:not(.active):hover {
            background-color: #e2e6ea;
            color: #272c33 !important;
        }
        .logout-btn {
            background-color: #dc3545;
            color: white;
            transition: background-color 0.2s ease, transform 0.2s ease;
        }
        .logout-btn:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }
    </style>
</head>
<body class="font-sans antialiased bg-light">
    <div class="min-h-screen">

        <!-- Navigation -->
        <nav class="bg-white shadow-sm mb-4">
            <div class="container d-flex justify-content-start align-items-center py-3 gap-3">
                <a href="{{ route('attendance.index') }}" class="btn nav-btn {{ request()->routeIs('attendance.*') ? 'active' : '' }}">
                    Attendance
                </a>
                <a href="{{ route('places.index') }}" class="btn nav-btn {{ request()->routeIs('places.*') ? 'active' : '' }}">
                    Places
                </a>
                <a href="{{ route('profile.edit') }}" class="btn nav-btn {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                    Edit Profile
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}" class="d-inline ms-auto">
                    @csrf
                    <button type="submit" class="btn logout-btn">Logout</button>
                </form>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="container my-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="fw-bold text-gray-800 mb-1">Edit Profile</h1>
                    <p class="text-muted small mb-0">Manage your account settings and password</p>
                </div>
            </div>

            <!-- Flash Message -->
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> Profile updated successfully.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <!-- Update Profile Information -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Update Password -->
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- Delete User -->
            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </main>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(function () {
            $('.datatable').DataTable();
        });
    </script>
</body>
</html>
