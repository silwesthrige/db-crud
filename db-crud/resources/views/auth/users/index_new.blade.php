@extends('dashboard')

@section('title', 'User Management')

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
                                {{ $pendingUsers->count() }} Pending
                            </span>
                            <span class="badge bg-success rounded-pill px-3 py-2">
                                <i class="fas fa-check me-1"></i>
                                {{ $approvedUsers->count() }} Active
                            </span>
                            <span class="badge bg-secondary rounded-pill px-3 py-2">
                                <i class="fas fa-pause me-1"></i>
                                {{ $users->where('status', 'inactive')->count() }} Inactive
                            </span>
                            <span class="badge bg-danger rounded-pill px-3 py-2">
                                <i class="fas fa-times me-1"></i>
                                {{ $rejectedUsers->count() }} Rejected
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
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="user_ids[]" value="{{ $user->id }}" class="pending-checkbox" onchange="updateBulkButtons()">
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-sm bg-warning bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                            @if($user->role === 'admin')
                                                                <i class="fas fa-crown text-white" title="Administrator"></i>
                                                            @else
                                                                <i class="fas fa-user text-white" title="Regular User"></i>
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
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-success bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        @if($user->role === 'admin')
                                                            <i class="fas fa-crown text-white" title="Administrator"></i>
                                                        @else
                                                            <i class="fas fa-user text-white" title="Regular User"></i>
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
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-secondary bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        @if($user->role === 'admin')
                                                            <i class="fas fa-crown text-white" title="Administrator"></i>
                                                        @else
                                                            <i class="fas fa-user text-white" title="Regular User"></i>
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
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-sm bg-danger bg-opacity-20 rounded-circle d-flex align-items-center justify-content-center me-3">
                                                        @if($user->role === 'admin')
                                                            <i class="fas fa-crown text-white" title="Administrator"></i>
                                                        @else
                                                            <i class="fas fa-user text-white" title="Regular User"></i>
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
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">
                    <i class="fas fa-times-circle text-danger me-2"></i>
                    Reject User
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>You are about to reject <strong id="rejectUserName"></strong>.</p>
                    <div class="mb-3">
                        <label for="rejectionReason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejectionReason" name="rejection_reason" rows="3" 
                                  placeholder="Please provide a reason for rejection..." required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
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
    <div class="modal-dialog">
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
// Show individual reject modal
function showRejectModal(userId, userName) {
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = '/admin/users/' + userId + '/reject';
    new bootstrap.Modal(document.getElementById('rejectModal')).show();
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
    
    new bootstrap.Modal(document.getElementById('bulkRejectModal')).show();
}
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
}

.avatar-sm i {
    font-size: 1.1rem;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
    font-weight: 900;
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

/* Enhanced white icon visibility */
.text-white {
    color: #ffffff !important;
    text-shadow: 1px 1px 3px rgba(0,0,0,0.4);
}

/* Avatar background enhancements for better icon contrast */
.bg-warning.bg-opacity-20 {
    background-color: rgba(255, 193, 7, 0.3) !important;
    border: 1px solid rgba(255, 193, 7, 0.4);
}

.bg-success.bg-opacity-20 {
    background-color: rgba(25, 135, 84, 0.3) !important;
    border: 1px solid rgba(25, 135, 84, 0.4);
}

.bg-secondary.bg-opacity-20 {
    background-color: rgba(108, 117, 125, 0.3) !important;
    border: 1px solid rgba(108, 117, 125, 0.4);
}

.bg-danger.bg-opacity-20 {
    background-color: rgba(220, 53, 69, 0.3) !important;
    border: 1px solid rgba(220, 53, 69, 0.4);
}
</style>
@endsection
