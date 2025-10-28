<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Attendance Management</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

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
<body class="bg-light">

<!-- Custom Navigation Header -->
<nav class="bg-white shadow-sm mb-4">
    <div class="container d-flex justify-content-start align-items-center py-3 gap-3">
        <a href="{{ route('attendance.index') }}" class="btn nav-btn {{ request()->routeIs('attendance.*') ? 'active' : '' }}">Attendance</a>
        <a href="{{ route('places.index') }}" class="btn nav-btn {{ request()->routeIs('places.*') ? 'active' : '' }}">Places</a>
        <a href="{{ route('profile.edit') }}" class="btn nav-btn {{ request()->routeIs('profile.*') ? 'active' : '' }}">Edit Profile</a>
        <form method="POST" action="{{ route('logout') }}" class="d-inline ms-auto">
            @csrf
            <button type="submit" class="btn logout-btn">Logout</button>
        </form>
    </div>
</nav>

<div class="container my-5">

    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold">Attendance Management</h2>
        </div>

        @php
            $hasActive = auth()->user()->attendances()
                            ->whereNull('check_out')
                            ->exists();
        @endphp

        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#checkinModal"
            @if(auth()->user()->places->isEmpty() || $hasActive)
                disabled title="{{ $hasActive ? 'You must check out before checking in again' : 'No places assigned' }}"
            @endif>
            <i class="bi bi-box-arrow-in-right me-1"></i> Check-In
        </button>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Attendance Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="attendanceTable" class="table table-striped table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User ID</th> 
                            <th>Date</th>
                            <th>Place</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($attendances as $attendance)
                        <tr>
                            <td>{{ $attendance->user_id }}</td> 
                            <td>{{ $attendance->date->format('Y-m-d') }}</td>
                            <td>{{ $attendance->place?->residence }} - Block {{ $attendance->place?->block }}</td>
                            <td>{{ $attendance->check_in ? $attendance->check_in->format('Y-m-d H:i') : '-' }}</td>
                            <td>{{ $attendance->check_out ? $attendance->check_out->format('Y-m-d H:i') : '-' }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    @if(!$attendance->check_out)
                                        <form method="POST" action="{{ route('attendance.checkout', $attendance->id) }}">
                                            @csrf
                                            <button type="submit" class="btn btn-info btn-sm">
                                                <i class="bi bi-box-arrow-right me-1"></i> Check-Out
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-danger btn-sm deleteBtn" data-id="{{ $attendance->id }}">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No attendance records found</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Check-In Modal -->
<div class="modal fade" id="checkinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('attendance.checkin') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Check-In</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="place_id" class="form-label">Select Place</label>
                        <select name="place_id" id="place_id" class="form-select" required>
                            @foreach(auth()->user()->places as $place)
                                <option value="{{ $place->id }}">{{ $place->residence }} - Block {{ $place->block }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="text" class="form-control" value="{{ now()->format('Y-m-d') }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Check-In Time</label>
                        <input type="text" class="form-control" id="current_time" readonly>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Check-In
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this record?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#attendanceTable').DataTable({
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', {
            extend: 'print',
            exportOptions: { columns: ':not(:last-child)' }
        }],
        columnDefs: [
            { orderable: false, targets: 5 }
        ]
    });

 
    const currentTimeInput = document.getElementById('current_time');
    if (currentTimeInput) {
        function updateTime() {
            const now = new Date(new Date().toLocaleString("en-US", { timeZone: "Asia/Kuala_Lumpur" }));
            currentTimeInput.value = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
        }
        updateTime();
        setInterval(updateTime, 1000);
    }

    // Delete modal
    $('.deleteBtn').click(function() {
        const id = $(this).data('id');
        $('#deleteForm').attr('action', "{{ url('attendance') }}/" + id);
        $('#deleteModal').modal('show');
    });
});
</script>

</body>
</html>
