<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Place Management</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        .nav-btn { border-radius: 0.5rem; padding: 0.5rem 1rem; font-weight: 500; transition: background-color 0.2s ease, color 0.2s ease; }
        .nav-btn.active { background-color: #2e3238; color: white !important; }
        .nav-btn:not(.active):hover { background-color: #e2e6ea; color: #272c33 !important; }
        .logout-btn { background-color: #dc3545; color: white; transition: background-color 0.2s ease, transform 0.2s ease; }
        .logout-btn:hover { background-color: #c82333; transform: scale(1.05); }
    </style>
</head>
<body class="bg-light">

<!-- Navigation -->
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
            <h2 class="fw-bold">Place Management</h2>
        </div>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addPlaceModal">
            <i class="bi bi-plus-lg me-1"></i> Add Place
        </button>
    </div>

    <!-- Flash Message -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Places Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="placesTable" class="table table-striped table-hover table-bordered align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>User ID</th>
                            <th>Residence</th>
                            <th>Block</th>
                            <th class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse(auth()->user()->places as $place)
                        <tr>
                            <td>{{ $place->user_id }}</td>
                            <td>{{ $place->residence }}</td>
                            <td>{{ $place->block }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editPlaceModal{{ $place->id }}">
                                        <i class="bi bi-pencil me-1"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm deleteBtn" data-id="{{ $place->id }}">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td class="text-center">No places assigned to you</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<!-- Add Place Modal -->
<div class="modal fade" id="addPlaceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('places.store') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ auth()->id() }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Place</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Assigned User</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Residence</label>
                        <input type="text" name="residence" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Block</label>
                        <input type="text" name="block" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save Place</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Edit Place Modals -->
@foreach(auth()->user()->places as $place)
<div class="modal fade" id="editPlaceModal{{ $place->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('places.update', $place->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Place</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Assigned User</label>
                        <input type="text" class="form-control" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Residence</label>
                        <input type="text" name="residence" class="form-control" value="{{ $place->residence }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Block</label>
                        <input type="text" name="block" class="form-control" value="{{ $place->block }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Update Place</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

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
                    Are you sure you want to delete this place?
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
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#placesTable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf',
            { extend: 'print', exportOptions: { columns: ':not(:last-child)' } }
        ],
        columnDefs: [{ orderable: false, targets: 3 }] // Actions column
    });

    $('.deleteBtn').click(function() {
        const id = $(this).data('id');
        const url = "{{ url('places') }}/" + id;
        $('#deleteForm').attr('action', url);
        $('#deleteModal').modal('show');
    });
});
</script>

</body>
</html>
