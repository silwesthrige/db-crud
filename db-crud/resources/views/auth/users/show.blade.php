@extends('templates.admin-master')

@section('header_content')
<title>User Details - {{ $user->name }}</title>
@endsection

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to User Management
            </a>
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

    <!-- User Details Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary bg-opacity-10 border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-user-circle text-primary me-2"></i>
                            User Profile: {{ $user->name }}
                        </h5>
                        <div class="d-flex align-items-center gap-2">
                            @if($user->status === 'pending')
                                <span class="badge bg-warning px-3 py-2 shadow-sm">
                                    <i class="fas fa-clock me-1"></i>Pending Approval
                                </span>
                            @elseif($user->status === 'approved')
                                <span class="badge bg-success px-3 py-2 shadow-sm">
                                    <i class="fas fa-check-circle me-1"></i>Active
                                </span>
                            @elseif($user->status === 'rejected')
                                <span class="badge bg-danger px-3 py-2 shadow-sm">
                                    <i class="fas fa-times-circle me-1"></i>Rejected
                                </span>
                            @elseif($user->status === 'inactive')
                                <span class="badge bg-secondary px-3 py-2 shadow-sm">
                                    <i class="fas fa-pause-circle me-1"></i>Inactive
                                </span>
                            @endif
                            
                            @if($user->role === 'admin')
                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 shadow-sm">
                                    <i class="fas fa-crown me-1"></i>Administrator
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <div class="avatar-lg 
                                @if($user->status === 'pending') bg-warning bg-opacity-30 border border-warning
                                @elseif($user->status === 'approved') bg-success bg-opacity-30 border border-success
                                @elseif($user->status === 'rejected') bg-danger bg-opacity-30 border border-danger
                                @elseif($user->status === 'inactive') bg-secondary bg-opacity-30 border border-secondary
                                @else bg-primary bg-opacity-30 border border-primary
                                @endif
                                rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm">
                                @if($user->role === 'admin')
                                    <i class="fas fa-crown text-white" style="font-size: 2.5rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.4);" title="Administrator"></i>
                                @else
                                    <i class="fas fa-user text-white" style="font-size: 2.5rem; text-shadow: 1px 1px 3px rgba(0,0,0,0.4);" title="Regular User"></i>
                                @endif
                            </div>
                            <h6 class="mb-1">{{ $user->name }}</h6>
                            <small class="text-muted">
                                @if($user->role === 'admin')
                                    <i class="fas fa-crown text-warning me-1"></i>
                                @else
                                    <i class="fas fa-user text-muted me-1"></i>
                                @endif
                                {{ ucfirst($user->role) }}
                            </small>
                        </div>
                        <div class="col-md-9">
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Full Name:</strong></div>
                                <div class="col-sm-8">{{ $user->name }}</div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Email Address:</strong></div>
                                <div class="col-sm-8">
                                    <a href="mailto:{{ $user->email }}" class="text-decoration-none">
                                        <i class="fas fa-envelope me-1"></i>{{ $user->email }}
                                    </a>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Phone Number:</strong></div>
                                <div class="col-sm-8">
                                    @if($user->phone)
                                        <a href="tel:{{ $user->phone }}" class="text-decoration-none">
                                            <i class="fas fa-phone me-1"></i>{{ $user->phone }}
                                        </a>
                                    @else
                                        <span class="text-muted">Not provided</span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Role:</strong></div>
                                <div class="col-sm-8">
                                    @if($user->role === 'admin')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-crown me-1"></i>{{ ucfirst($user->role) }}
                                        </span>
                                    @else
                                        <span class="badge bg-primary">
                                            <i class="fas fa-user me-1"></i>{{ ucfirst($user->role) }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Account Status:</strong></div>
                                <div class="col-sm-8">
                                    @if($user->status === 'pending')
                                        <span class="badge bg-warning">
                                            <i class="fas fa-clock me-1"></i>Pending Approval
                                        </span>
                                    @elseif($user->status === 'approved')
                                        <span class="badge bg-success">
                                            <i class="fas fa-check-circle me-1"></i>Active
                                        </span>
                                    @elseif($user->status === 'rejected')
                                        <span class="badge bg-danger">
                                            <i class="fas fa-times-circle me-1"></i>Rejected
                                        </span>
                                    @elseif($user->status === 'inactive')
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-pause-circle me-1"></i>Inactive
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Registration Date:</strong></div>
                                <div class="col-sm-8">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $user->created_at->format('F j, Y') }} at {{ $user->created_at->format('g:i A') }}
                                    <small class="text-muted">({{ $user->created_at->diffForHumans() }})</small>
                                </div>
                            </div>
                            @if($user->approved_at)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Approval Date:</strong></div>
                                    <div class="col-sm-8">
                                        <i class="fas fa-check-circle me-1"></i>
                                        {{ $user->approved_at->format('F j, Y') }} at {{ $user->approved_at->format('g:i A') }}
                                        <small class="text-muted">({{ $user->approved_at->diffForHumans() }})</small>
                                    </div>
                                </div>
                            @endif
                            @if($user->approver)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Approved By:</strong></div>
                                    <div class="col-sm-8">
                                        <i class="fas fa-user-shield me-1"></i>
                                        {{ $user->approver->name }} ({{ $user->approver->email }})
                                    </div>
                                </div>
                            @endif
                            @if($user->rejection_reason)
                                <div class="row mb-3">
                                    <div class="col-sm-4"><strong>Rejection Reason:</strong></div>
                                    <div class="col-sm-8">
                                        <div class="alert alert-danger mb-0">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            {{ $user->rejection_reason }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-secondary bg-opacity-10 border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($user->status === 'pending')
                            <!-- Approve Action -->
                            <form method="POST" action="{{ route('admin.users.approve', $user) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Are you sure you want to approve this user?')">
                                    <i class="fas fa-check me-2"></i>Approve User
                                </button>
                            </form>
                            
                            <!-- Reject Action -->
                            <button type="button" class="btn btn-danger w-100" 
                                    onclick="showRejectModal({{ $user->id }}, '{{ $user->name }}')">
                                <i class="fas fa-times me-2"></i>Reject User
                            </button>
                        @elseif($user->status === 'approved' && !$user->isAdmin())
                            <!-- Deactivate Action -->
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning w-100" 
                                        onclick="return confirm('Are you sure you want to deactivate this user?')">
                                    <i class="fas fa-pause me-2"></i>Deactivate User
                                </button>
                            </form>
                        @elseif($user->status === 'inactive')
                            <!-- Activate Action -->
                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success w-100" 
                                        onclick="return confirm('Are you sure you want to activate this user?')">
                                    <i class="fas fa-play me-2"></i>Activate User
                                </button>
                            </form>
                        @elseif($user->status === 'rejected')
                            <!-- Reactivate Action -->
                            <form method="POST" action="{{ route('admin.users.reactivate', $user) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning w-100" 
                                        onclick="return confirm('Are you sure you want to reactivate this user? They will be moved to pending status.')">
                                    <i class="fas fa-redo me-2"></i>Reactivate User
                                </button>
                            </form>
                        @endif

                        @if(!$user->isAdmin())
                            <!-- Delete Action -->
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100" 
                                        onclick="return confirm('Are you sure you want to permanently delete this user? This action cannot be undone.')">
                                    <i class="fas fa-trash me-2"></i>Delete User
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- User Statistics Card -->
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-header bg-info bg-opacity-10 border-0">
                    <h6 class="card-title mb-0">
                        <i class="fas fa-chart-bar me-2"></i>User Statistics
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-calendar-alt text-primary" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="text-primary mb-1">{{ $user->events()->count() }}</h4>
                                <small class="text-muted">Events Created</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-bell text-info" style="font-size: 1.5rem;"></i>
                                </div>
                                <h4 class="text-info mb-1">{{ $user->notifications()->count() }}</h4>
                                <small class="text-muted">Notifications</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-12">
                            <div class="stat-item">
                                <div class="stat-icon mb-2">
                                    <i class="fas fa-clock text-secondary" style="font-size: 1.2rem;"></i>
                                </div>
                                <h6 class="text-secondary mb-1">Last Activity</h6>
                                <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
            <form id="rejectForm" method="POST">
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
                        <i class="fas fa-times me-1"></i>Reject User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Ensure DOM is ready and clean up any modal issues
document.addEventListener('DOMContentLoaded', function() {
    console.log('User details page loaded');
    
    // Clean up any stray modal backdrops on page load
    const strayBackdrops = document.querySelectorAll('.modal-backdrop');
    strayBackdrops.forEach(backdrop => backdrop.remove());
    
    // Reset body classes
    document.body.classList.remove('modal-open');
    document.body.style.overflow = '';
    document.body.style.paddingRight = '';
    
    // Initialize modal event listeners
    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('hidden.bs.modal', function () {
            // Clean up when modal is hidden
            const backdrops = document.querySelectorAll('.modal-backdrop');
            backdrops.forEach(backdrop => backdrop.remove());
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        });
    }
});

// Show reject modal
function showRejectModal(userId, userName) {
    console.log('Opening reject modal for user:', userId, userName);
    
    // First, ensure any existing modals are properly closed
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
    
    const formAction = '{{ url("/admin/users") }}/' + userId + '/reject';
    
    document.getElementById('rejectUserName').textContent = userName;
    document.getElementById('rejectForm').action = formAction;
    document.getElementById('rejectionReason').value = '';
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
document.getElementById('rejectForm').addEventListener('submit', function(event) {
    const reason = document.getElementById('rejectionReason').value.trim();
    const submitBtn = document.getElementById('rejectSubmitBtn');
    
    if (reason.length < 5) {
        event.preventDefault();
        document.getElementById('rejectionReason').classList.add('is-invalid');
        document.getElementById('rejectionReasonError').textContent = 'Please provide a detailed reason (at least 5 characters).';
        return false;
    }
    
    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Rejecting...';
    
    return true;
});
</script>

<style>
.avatar-lg {
    width: 90px;
    height: 90px;
    border: 3px solid rgba(255, 255, 255, 0.2);
    transition: all 0.3s ease;
}

.avatar-lg:hover {
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.15) !important;
}

.stat-item {
    transition: all 0.3s ease;
    padding: 1rem;
    border-radius: 10px;
}

.stat-item:hover {
    background-color: rgba(0,0,0,0.02);
    transform: translateY(-2px);
}

.stat-item h4 {
    font-weight: 700;
    font-size: 2rem;
}

.stat-icon {
    opacity: 0.8;
    transition: all 0.3s ease;
}

.stat-item:hover .stat-icon {
    opacity: 1;
    transform: scale(1.1);
}

.card {
    transition: all 0.3s ease;
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

/* Enhanced avatar styles for better icon visibility */
.avatar-lg {
    width: 80px;
    height: 80px;
}

.avatar-lg i {
    font-weight: 900;
}

/* Enhanced white icon visibility */
.text-white {
    color: #ffffff !important;
}

/* Avatar background enhancements for better icon contrast */
.bg-warning.bg-opacity-30 {
    background-color: rgba(255, 193, 7, 0.4) !important;
}

.bg-success.bg-opacity-30 {
    background-color: rgba(25, 135, 84, 0.4) !important;
}

.bg-secondary.bg-opacity-30 {
    background-color: rgba(108, 117, 125, 0.4) !important;
}

.bg-danger.bg-opacity-30 {
    background-color: rgba(220, 53, 69, 0.4) !important;
}

.bg-primary.bg-opacity-30 {
    background-color: rgba(13, 110, 253, 0.4) !important;
}
</style>
@endsection
