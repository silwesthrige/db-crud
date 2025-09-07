@extends('templates.admin-master')

@section('header_content')
<title>User Management</title>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title mb-1">
                                <i class="fas fa-users text-primary me-2"></i>
                                User Management
                            </h3>
                            <p class="text-muted mb-0">Manage user registrations and approvals</p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-warning rounded-pill px-3 py-2">
                                <i class="fas fa-clock me-1"></i>
                                <span id="pendingCount">{{ $pendingUsers->count() }}</span> Pending
                            </span>
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-check me-1"></i>
                                <span id="approvedCount">{{ $approvedUsers->count() }}</span> Active
                            </span>
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="fas fa-pause me-1"></i>
                                <span id="inactiveCount">{{ $users->where('status', 'inactive')->count() }}</span> Inactive
                            </span>
                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                <i class="fas fa-times me-1"></i>
                                <span id="rejectedCount">{{ $rejectedUsers->count() }}</span> Rejected
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-search me-2"></i>Search & Filter Users
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="searchInput" class="form-label">Search Users</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" 
                                       placeholder="Search by name, email, or phone..." onkeyup="filterUsers()">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="statusFilter" class="form-label">Filter by Status</label>
                            <select class="form-select" id="statusFilter" onchange="filterUsers()">
                                <option value="">All Statuses</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="roleFilter" class="form-label">Filter by Role</label>
                            <select class="form-select" id="roleFilter" onchange="filterUsers()">
                                <option value="">All Roles</option>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sortOrder" class="form-label">Sort Order</label>
                            <select class="form-select" id="sortOrder" onchange="filterUsers()">
                                <option value="newest">Newest First</option>
                                <option value="oldest">Oldest First</option>
                                <option value="name-asc">Name A-Z</option>
                                <option value="name-desc">Name Z-A</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-12">
                            <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()">
                                <i class="fas fa-eraser me-1"></i>Clear Filters
                            </button>
                            <span class="ms-3 text-muted" id="filterResults">
                                Showing all users
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Pending Users Section -->
    @if($pendingUsers->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-warning bg-opacity-10 border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">
                                <i class="fas fa-clock text-warning me-2"></i>
                                Pending Approvals ({{ $pendingUsers->count() }})
                            </h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-sm btn-success" onclick="selectAllPending()">
                                    <i class="fas fa-check-double me-1"></i>
                                    Select All
                                </button>
                                <button type="button" class="btn btn-sm btn-success" onclick="bulkApprove()" id="bulkApproveBtn" disabled>
                                    <i class="fas fa-check me-1"></i>
                                    Bulk Approve
                                </button>
                                <button type="button" class="btn btn-sm btn-danger" onclick="bulkReject()" id="bulkRejectBtn" disabled>
                                    <i class="fas fa-times me-1"></i>
                                    Bulk Reject
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <form id="bulkActionForm">
                            @csrf
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th width="50">
                                                <input type="checkbox" id="selectAllPendingCheck" onchange="toggleAllPending()">
                                            </th>
                                            <th>User</th>
                                            <th>Email</th>
                                            <th>Registration Date</th>
                                            <th>Role</th>
                                            <th width="200">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pendingUsers as $user)
                                            <tr data-user-row 
                                                data-user-name="{{ $user->name }}" 
                                                data-user-email="{{ $user->email }}" 
                                                data-user-phone="{{ $user->phone ?? '' }}" 
                                                data-user-status="{{ $user->status }}" 
                                                data-user-role="{{ $user->role }}" 
                                                data-user-created="{{ $user->created_at->toISOString() }}">
                                                <td>
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="pending-checkbox user-checkbox" onchange="updateBulkButtons(); updateBulkActions();">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-warning bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            @if($user->role === 'admin')
                                                                <i class="fas fa-crown text-warning" title="Administrator"></i>
                                                            @elseif(str_contains(strtolower($user->name), 'system'))
                                                                <i class="fas fa-cogs text-warning" title="System User"></i>
                                                            @elseif(str_contains(strtolower($user->name), 'test'))
                                                                <i class="fas fa-flask text-warning" title="Test User"></i>
                                                            @else
                                                                <i class="fas fa-user text-warning" title="Regular User"></i>
                                                            @endif
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $user->name }}</h6>
                                                            <small class="text-muted">{{ $user->phone ?? 'No phone' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ $user->email }}</td>
                                                <td>
                                                    <small class="text-muted">{{ $user->created_at->format('M d, Y') }}</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="d-inline">
                                                            @csrf
                                                            <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                                                <i class="fas fa-check"></i>
                                                            </button>
                                                        </form>
                                                        <button type="button" class="btn btn-sm btn-danger" onclick="showRejectModal({{ $user->id }}, '{{ $user->name }}')" title="Reject">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="View Details">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Approved Users Section -->
    @if($approvedUsers->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-success bg-opacity-10 border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Active Users ({{ $approvedUsers->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Approved Date</th>
                                        <th>Approved By</th>
                                        <th>Status</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($approvedUsers as $user)
                                        <tr data-user-row 
                                            data-user-name="{{ $user->name }}" 
                                            data-user-email="{{ $user->email }}" 
                                            data-user-phone="{{ $user->phone ?? '' }}" 
                                            data-user-status="{{ $user->status }}" 
                                            data-user-role="{{ $user->role }}" 
                                            data-user-created="{{ $user->created_at->toISOString() }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        @if($user->role === 'admin')
                                                            <i class="fas fa-crown text-success" title="Administrator"></i>
                                                        @elseif(str_contains(strtolower($user->name), 'system'))
                                                            <i class="fas fa-cogs text-success" title="System User"></i>
                                                        @elseif(str_contains(strtolower($user->name), 'test'))
                                                            <i class="fas fa-flask text-success" title="Test User"></i>
                                                        @else
                                                            <i class="fas fa-user text-success" title="Regular User"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->phone ?? 'No phone' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <small class="text-muted">{{ $user->approved_at ? $user->approved_at->format('M d, Y') : 'N/A' }}</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->approver ? $user->approver->name : 'System' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-success">Active</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    @if(!$user->isAdmin())
                                                        <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-warning" title="Deactivate User" 
                                                                    onclick="return confirm('Are you sure you want to deactivate this user?')">
                                                                <i class="fas fa-pause"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(!$user->isAdmin())
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Delete User" 
                                                                    onclick="return confirm('Are you sure you want to permanently delete this user?')">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Inactive Users Section -->
    @if($users->where('status', 'inactive')->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-secondary bg-opacity-10 border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-pause-circle text-secondary me-2"></i>
                            Inactive Users ({{ $users->where('status', 'inactive')->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Deactivated Date</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users->where('status', 'inactive') as $user)
                                        <tr data-user-row 
                                            data-user-name="{{ $user->name }}" 
                                            data-user-email="{{ $user->email }}" 
                                            data-user-phone="{{ $user->phone ?? '' }}" 
                                            data-user-status="{{ $user->status }}" 
                                            data-user-role="{{ $user->role }}" 
                                            data-user-created="{{ $user->created_at->toISOString() }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-secondary bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        @if($user->role === 'admin')
                                                            <i class="fas fa-crown text-secondary" title="Administrator"></i>
                                                        @elseif(str_contains(strtolower($user->name), 'system'))
                                                            <i class="fas fa-cogs text-secondary" title="System User"></i>
                                                        @elseif(str_contains(strtolower($user->name), 'test'))
                                                            <i class="fas fa-flask text-secondary" title="Test User"></i>
                                                        @else
                                                            <i class="fas fa-user text-secondary" title="Regular User"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->phone ?? 'No phone' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <small class="text-muted">{{ $user->updated_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">{{ ucfirst($user->role) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">Inactive</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-success" title="Activate User" 
                                                                onclick="return confirm('Are you sure you want to activate this user?')">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete User" 
                                                                onclick="return confirm('Are you sure you want to permanently delete this user?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Rejected Users Section -->
    @if($rejectedUsers->count() > 0)
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-danger bg-opacity-10 border-0">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-times-circle text-danger me-2"></i>
                            Rejected Users ({{ $rejectedUsers->count() }})
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Email</th>
                                        <th>Rejected Date</th>
                                        <th>Rejection Reason</th>
                                        <th>Status</th>
                                        <th width="200">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rejectedUsers as $user)
                                        <tr data-user-row 
                                            data-user-name="{{ $user->name }}" 
                                            data-user-email="{{ $user->email }}" 
                                            data-user-phone="{{ $user->phone ?? '' }}" 
                                            data-user-status="{{ $user->status }}" 
                                            data-user-role="{{ $user->role }}" 
                                            data-user-created="{{ $user->created_at->toISOString() }}">
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-danger bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        @if($user->role === 'admin')
                                                            <i class="fas fa-crown text-danger" title="Administrator"></i>
                                                        @elseif(str_contains(strtolower($user->name), 'system'))
                                                            <i class="fas fa-cogs text-danger" title="System User"></i>
                                                        @elseif(str_contains(strtolower($user->name), 'test'))
                                                            <i class="fas fa-flask text-danger" title="Test User"></i>
                                                        @else
                                                            <i class="fas fa-user text-danger" title="Regular User"></i>
                                                        @endif
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0">{{ $user->name }}</h6>
                                                        <small class="text-muted">{{ $user->phone ?? 'No phone' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $user->email }}</td>
                                            <td>
                                                <small class="text-muted">{{ $user->updated_at->format('M d, Y') }}</small>
                                            </td>
                                            <td>
                                                <small class="text-muted">{{ $user->rejection_reason ?? 'No reason provided' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-danger">Rejected</span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="btn btn-sm btn-warning" title="Reactivate (Move to Pending)" 
                                                                onclick="return confirm('Are you sure you want to reactivate this user? They will be moved to pending status.')">
                                                            <i class="fas fa-redo"></i>
                                                        </button>
                                                    </form>
                                                    <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info" title="View Details">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete User" 
                                                                onclick="return confirm('Are you sure you want to permanently delete this user?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($users->where('status', '!=', 'admin')->count() == 0)
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-users text-muted mb-3" style="font-size: 4rem;"></i>
                        <h4 class="text-muted mb-2">No Users Found</h4>
                        <p class="text-muted">No user registrations to manage at this time.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    Reject User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST" onsubmit="return handleRejectSubmit(event)">
                @csrf
                <div class="modal-body">
                    <p>You are about to reject <strong id="rejectUserName"></strong>.</p>
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                        <div class="invalid-feedback" id="rejectionReasonError"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="rejectSubmitBtn">
                        <i class="fas fa-times me-1"></i>
                        Reject User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Bulk Reject Modal -->
<div class="modal fade" id="bulkRejectModal" tabindex="-1" aria-labelledby="bulkRejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bulkRejectModalLabel">
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    Bulk Reject Users
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="bulkRejectForm" method="POST" action="{{ route('admin.users.bulk-reject') }}">
                @csrf
                <div class="modal-body">
                    <p>You are about to reject <strong id="bulkRejectCount"></strong> users.</p>
                    <div class="mb-3">
                        <label for="bulkRejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="bulkRejectionReason" name="rejection_reason" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                    <div id="bulkRejectUserIds"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>
                        Reject Selected Users
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Ensure DOM is ready and clean up any modal issues
document.addEventListener('DOMContentLoaded', function() {
    console.log('User management page loaded');
    
    // Clean up any stray modal backdrops on page load
    const strayBackdrops = document.querySelectorAll('.modal-backdrop');
    strayBackdrops.forEach(backdrop => backdrop.remove());
    
    // Reset body classes
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Initialize filter results count
    updateFilterResults();
    updateStatusCounters();
    
    // Initialize modal event listeners
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('shown.bs.modal', function () {
            // Force modal to be interactive
            const modal = document.querySelector('.modal.show');
            const modalDialog = modal?.querySelector('.modal-dialog');
            const modalContent = modal?.querySelector('.modal-content');
            
            if (modal) {
                modal.style.pointerEvents = 'auto';
                modal.style.zIndex = '1060';
            }
            if (modalDialog) {
                modalDialog.style.pointerEvents = 'auto';
                modalDialog.style.zIndex = '1061';
            }
            if (modalContent) {
                modalContent.style.pointerEvents = 'auto';
                modalContent.style.zIndex = '1062';
            }
            
            // Remove any blocking elements
            const allElements = modal?.querySelectorAll('*');
            allElements?.forEach(el => {
                el.style.pointerEvents = 'auto';
            });
        });
        
        rejectModal.addEventListener('hidden.bs.modal', function () {
            // Clean up when modal is hidden
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
    
    const bulkRejectModal = document.getElementById('bulkRejectModal');
    if (bulkRejectModal) {
        bulkRejectModal.addEventListener('shown.bs.modal', function () {
            // Force modal to be interactive
            const modal = document.querySelector('.modal.show');
            const modalDialog = modal?.querySelector('.modal-dialog');
            const modalContent = modal?.querySelector('.modal-content');
            
            if (modal) {
                modal.style.pointerEvents = 'auto';
                modal.style.zIndex = '1060';
            }
            if (modalDialog) {
                modalDialog.style.pointerEvents = 'auto';
                modalDialog.style.zIndex = '1061';
            }
            if (modalContent) {
                modalContent.style.pointerEvents = 'auto';
                modalContent.style.zIndex = '1062';
            }
            
            // Remove any blocking elements
            const allElements = modal?.querySelectorAll('*');
            allElements?.forEach(el => {
                el.style.pointerEvents = 'auto';
            });
        });
        
        bulkRejectModal.addEventListener('hidden.bs.modal', function () {
            // Clean up when modal is hidden
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
});

// Show individual reject modal
function showRejectModal(userId, userName) {
    console.log('Opening reject modal for user:', userId, userName); // Debug log
    
    // First, ensure any existing modals are properly closed
    const existingModals = document.querySelectorAll('.modal.show');
    existingModals.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    });
    
    // Remove any existing backdrops immediately
    setTimeout(() => {
        const existingBackdrops = document.querySelectorAll('.modal-backdrop');
        existingBackdrops.forEach(backdrop => backdrop.remove());
        
        // Reset body classes
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }, 50);
    
    const formAction = '{{ url("/admin/users") }}/' + userId + '/reject';
    console.log('Setting form action to:', formAction); // Debug log
    
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = formAction;
    document.getElementById('rejectionReason').value = ''; // Clear previous value
    document.getElementById('rejectionReason').classList.remove('is-invalid');
    
    // Reset submit button
    const submitBtn = document.getElementById('rejectSubmitBtn');
    submitBtn.disabled = false;
    submitBtn.innerHTML = '<i class="fas fa-times me-1"></i> Reject User';
    
    // Create new modal instance with simpler options
    const modalElement = document.getElementById('rejectModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Show modal immediately
    modal.show();
    
    // Focus on textarea after modal is shown
    modalElement.addEventListener('shown.bs.modal', function () {
        document.getElementById('rejectionReason').focus();
    }, { once: true });
}

// Handle reject form submission
function handleRejectSubmit(event) {
    console.log('Reject form submitted'); // Debug log
    
    const reason = document.getElementById('rejectionReason').value.trim();
    const submitBtn = document.getElementById('rejectSubmitBtn');
    const form = document.getElementById('rejectForm');
    
    console.log('Rejection reason:', reason); // Debug log
    console.log('Form action:', form.action); // Debug log
    
    if (reason.length < 5) {
        event.preventDefault();
        document.getElementById('rejectionReason').classList.add('is-invalid');
        document.getElementById('rejectionReasonError').textContent = 'Please provide a detailed reason (at least 5 characters).';
        return false;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Rejecting...';
    
    console.log('Form will be submitted'); // Debug log
    return true; // Allow form submission
}

// Select all pending users
function selectAllPending() {
    const checkboxes = document.querySelectorAll('.pending-checkbox');
    const selectAllCheck = document.getElementById('selectAllPendingCheck');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = true;
    });
    selectAllCheck.checked = true;
    updateBulkButtons();
}

// Toggle all pending checkboxes
function toggleAllPending() {
    const selectAllCheck = document.getElementById('selectAllPendingCheck');
    const checkboxes = document.querySelectorAll('.pending-checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheck.checked;
    });
    updateBulkButtons();
}

// Update bulk action buttons
function updateBulkButtons() {
    const checkedBoxes = document.querySelectorAll('.pending-checkbox:checked');
    const bulkApproveBtn = document.getElementById('bulkApproveBtn');
    const bulkRejectBtn = document.getElementById('bulkRejectBtn');
    
    if (checkedBoxes.length > 0) {
        bulkApproveBtn.disabled = false;
        bulkRejectBtn.disabled = false;
    } else {
        bulkApproveBtn.disabled = true;
        bulkRejectBtn.disabled = true;
    }
}

// Bulk approve function
function bulkApprove() {
    const checkedBoxes = document.querySelectorAll('.pending-checkbox:checked');
    if (checkedBoxes.length === 0) return;
    
    if (confirm(`Are you sure you want to approve ${checkedBoxes.length} users?`)) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.users.bulk-approve") }}';
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add selected user IDs
        checkedBoxes.forEach(checkbox => {
            const userIdInput = document.createElement('input');
            userIdInput.type = 'hidden';
            userIdInput.name = 'user_ids[]';
            userIdInput.value = checkbox.value;
            form.appendChild(userIdInput);
        });
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Bulk reject function
function bulkReject() {
    const checkedBoxes = document.querySelectorAll('.pending-checkbox:checked');
    if (checkedBoxes.length === 0) return;
    
    // Clean up any existing modals first
    const existingModals = document.querySelectorAll('.modal.show');
    existingModals.forEach(modal => {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    });
    
    // Remove any existing backdrops
    setTimeout(() => {
        const existingBackdrops = document.querySelectorAll('.modal-backdrop');
        existingBackdrops.forEach(backdrop => backdrop.remove());
        
        // Reset body classes
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';
    }, 50);
    
    // Update bulk reject modal
    document.getElementById('bulkRejectCount').textContent = checkedBoxes.length;
    
    // Clear and populate user IDs
    const userIdsContainer = document.getElementById('bulkRejectUserIds');
    userIdsContainer.innerHTML = '';
    
    checkedBoxes.forEach(checkbox => {
        const userIdInput = document.createElement('input');
        userIdInput.type = 'hidden';
        userIdInput.name = 'user_ids[]';
        userIdInput.value = checkbox.value;
        userIdsContainer.appendChild(userIdInput);
    });
    
    // Create new modal instance with simpler options
    const modalElement = document.getElementById('bulkRejectModal');
    const modal = new bootstrap.Modal(modalElement);
    
    // Show modal immediately
    modal.show();
    
    // Focus on textarea after modal is shown
    modalElement.addEventListener('shown.bs.modal', function () {
        document.getElementById('bulkRejectionReason').focus();
    }, { once: true });
}

// Filter users function
function filterUsers() {
    const searchInput = document.getElementById('searchInput').value.toLowerCase();
    const statusFilter = document.getElementById('statusFilter').value;
    const roleFilter = document.getElementById('roleFilter').value;
    const sortOrder = document.getElementById('sortOrder').value;
    
    // Get all user rows
    const userRows = Array.from(document.querySelectorAll('[data-user-row]'));
    let visibleRows = [];
    
    userRows.forEach(row => {
        const userData = {
            name: row.getAttribute('data-user-name').toLowerCase(),
            email: row.getAttribute('data-user-email').toLowerCase(),
            phone: row.getAttribute('data-user-phone').toLowerCase(),
            status: row.getAttribute('data-user-status'),
            role: row.getAttribute('data-user-role'),
            created: new Date(row.getAttribute('data-user-created'))
        };
        
        let shouldShow = true;
        
        // Search filter
        if (searchInput) {
            const searchMatch = userData.name.includes(searchInput) || 
                              userData.email.includes(searchInput) || 
                              userData.phone.includes(searchInput);
            if (!searchMatch) shouldShow = false;
        }
        
        // Status filter
        if (statusFilter && userData.status !== statusFilter) {
            shouldShow = false;
        }
        
        // Role filter
        if (roleFilter && userData.role !== roleFilter) {
            shouldShow = false;
        }
        
        if (shouldShow) {
            visibleRows.push({ element: row, data: userData });
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
    
    // Sort visible rows
    visibleRows.sort((a, b) => {
        switch (sortOrder) {
            case 'oldest':
                return a.data.created - b.data.created;
            case 'name-asc':
                return a.data.name.localeCompare(b.data.name);
            case 'name-desc':
                return b.data.name.localeCompare(a.data.name);
            case 'newest':
            default:
                return b.data.created - a.data.created;
        }
    });
    
    // Reorder rows in DOM
    const container = userRows[0]?.parentNode;
    if (container) {
        visibleRows.forEach(({ element }) => {
            container.appendChild(element);
        });
    }
    
    // Update filter results
    updateFilterResults(visibleRows.length, userRows.length);
    
    // Show/hide empty state
    updateEmptyState(visibleRows.length);
    
    // Update counters in badges
    updateStatusCounters();
}

// Update filter results text
function updateFilterResults(visible = null, total = null) {
    const filterResultsElement = document.getElementById('filterResults');
    
    if (visible === null) {
        const userRows = document.querySelectorAll('[data-user-row]');
        total = userRows.length;
        visible = Array.from(userRows).filter(row => row.style.display !== 'none').length;
    }
    
    if (visible === total) {
        filterResultsElement.textContent = `Showing all ${total} users`;
    } else {
        filterResultsElement.textContent = `Showing ${visible} of ${total} users`;
    }
}

// Clear all filters
function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('sortOrder').value = 'newest';
    
    // Show all rows
    const userRows = document.querySelectorAll('[data-user-row]');
    userRows.forEach(row => {
        row.style.display = '';
    });
    
    updateFilterResults();
    updateEmptyState(userRows.length);
    updateStatusCounters();
}

// Update empty state display
function updateEmptyState(visibleCount) {
    let emptyState = document.getElementById('emptyState');
    
    if (visibleCount === 0) {
        if (!emptyState) {
            emptyState = document.createElement('div');
            emptyState.id = 'emptyState';
            emptyState.className = 'text-center py-5';
            emptyState.innerHTML = `
                <div class="row">
                    <div class="col-12">
                        <i class="fas fa-search text-muted" style="font-size: 3rem;"></i>
                        <h5 class="mt-3 text-muted">No users found</h5>
                        <p class="text-muted">Try adjusting your search criteria or filters</p>
                        <button type="button" class="btn btn-outline-primary" onclick="clearFilters()">
                            <i class="fas fa-eraser me-1"></i>Clear Filters
                        </button>
                    </div>
                </div>
            `;
            
            // Insert after the tabs
            const tabContent = document.querySelector('.tab-content');
            if (tabContent) {
                tabContent.appendChild(emptyState);
            }
        } else {
            emptyState.style.display = '';
        }
    } else if (emptyState) {
        emptyState.style.display = 'none';
    }
}

// Update status counters in badges
function updateStatusCounters() {
    const allRows = document.querySelectorAll('[data-user-row]');
    const visibleRows = Array.from(allRows).filter(row => row.style.display !== 'none');
    
    const counts = {
        pending: 0,
        approved: 0,
        inactive: 0,
        rejected: 0
    };
    
    visibleRows.forEach(row => {
        const status = row.getAttribute('data-user-status');
        if (counts.hasOwnProperty(status)) {
            counts[status]++;
        }
    });
    
    // Update badge counts
    const pendingCount = document.getElementById('pendingCount');
    const approvedCount = document.getElementById('approvedCount');
    const inactiveCount = document.getElementById('inactiveCount');
    const rejectedCount = document.getElementById('rejectedCount');
    
    if (pendingCount) pendingCount.textContent = counts.pending;
    if (approvedCount) approvedCount.textContent = counts.approved;
    if (inactiveCount) inactiveCount.textContent = counts.inactive;
    if (rejectedCount) rejectedCount.textContent = counts.rejected;
}
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.btn-group .btn {
    margin-right: 2px;
}

.btn-group .btn:last-child {
    margin-right: 0;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    font-weight: 600;
    color: #495057;
    border-bottom: 2px solid #dee2e6;
}

.badge {
    font-size: 0.75rem;
}

.alert {
    border: none;
    border-radius: 10px;
}

.modal-content {
    border-radius: 15px;
    border: none;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    z-index: 1060;
}

.modal-header {
    border-bottom: 1px solid #f0f0f0;
    padding: 1.5rem;
}

.modal-body {
    padding: 1.5rem;
}

.modal-footer {
    border-top: 1px solid #f0f0f0;
    padding: 1.5rem;
}

/* Fix for modal overlay issues */
.modal {
    z-index: 1055 !important;
}

.modal-backdrop {
    z-index: 1050 !important;
}

/* Ensure modal is always clickable */
.modal.show {
    z-index: 1060 !important;
}

.modal.show .modal-dialog {
    z-index: 1061 !important;
    pointer-events: auto;
}

.modal-dialog {
    pointer-events: none;
}

.modal-content {
    pointer-events: auto;
}

/* Prevent body scroll when modal is open */
body.modal-open {
    overflow: hidden !important;
}

/* Fix for multiple modal backdrops */
.modal-backdrop.show {
    opacity: 0.5 !important;
}

/* Ensure all modal elements are interactive */
.modal * {
    pointer-events: auto;
}

/* Button hover effects */
.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

/* Form validation styles */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    width: 100%;
    margin-top: 0.25rem;
    font-size: 0.875em;
    color: #dc3545;
}

/* Search and Filter Styles */
.form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.form-select:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #ced4da;
}

.badge.rounded-pill {
    font-size: 0.8rem;
    font-weight: 500;
}

/* Smooth transitions for filtered content */
[data-user-row] {
    transition: all 0.3s ease;
}

[data-user-row]:hover {
    background-color: rgba(0,0,0,0.02);
}

/* Empty state styling */
#emptyState {
    padding: 3rem 1rem;
    background-color: #f8f9fa;
    border-radius: 10px;
    margin: 2rem 0;
}

#emptyState .btn {
    transition: all 0.2s ease;
}

#emptyState .btn:hover {
    transform: translateY(-1px);
}

/* Status counter badges animation */
.badge {
    transition: all 0.3s ease;
}

/* Search input focus effects */
.input-group:focus-within .input-group-text {
    border-color: #86b7fe;
    background-color: rgba(13, 110, 253, 0.1);
}

/* Filter results text styling */
#filterResults {
    font-size: 0.9rem;
    font-weight: 500;
}

/* Enhanced icon visibility and styling */
.avatar-sm {
    width: 45px;
    height: 45px;
    border: 2px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s ease;
}

.avatar-sm:hover {
    transform: scale(1.05);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.avatar-sm i {
    font-size: 1.2rem !important;
    font-weight: 900;
}

/* Button group improvements */
.btn-group .btn {
    margin-right: 2px;
    min-width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.btn-group .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.btn-group .btn i {
    font-size: 0.9rem;
    font-weight: 900;
}

/* Status badge improvements */
.badge {
    font-weight: 600;
    letter-spacing: 0.025em;
}

/* Table hover effects */
.table tbody tr:hover {
    background-color: rgba(0,0,0,0.02);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}

/* Icon color improvements for better visibility */
.text-warning {
    color: #f39c12 !important;
}

.text-success {
    color: #27ae60 !important;
}

.text-danger {
    color: #e74c3c !important;
}

.text-info {
    color: #3498db !important;
}

.text-secondary {
    color: #6c757d !important;
}

/* User avatar specific styling */
.bg-warning.bg-opacity-20 i {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.bg-success.bg-opacity-20 i {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.bg-danger.bg-opacity-20 i {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

.bg-secondary.bg-opacity-20 i {
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}
</style>
@endsection
