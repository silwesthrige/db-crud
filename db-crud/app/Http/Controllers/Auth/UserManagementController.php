<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserManagementController extends Controller
{
    /**
     * Display pending users for approval
     */
    public function index()
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all users for status filtering
        $users = User::all();
        
        // Separate users by status for better performance
        $pendingUsers = $users->where('status', 'pending');
        $approvedUsers = $users->where('status', 'approved');
        $rejectedUsers = $users->where('status', 'rejected');
        
        return view('auth.users.index', compact('users', 'pendingUsers', 'approvedUsers', 'rejectedUsers'));
    }

    /**
     * Approve a user
     */
    public function approve(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
            'rejection_reason' => null,
        ]);

        // Create notification for user approval
        \App\Models\Notification::create([
            'type' => 'success',
            'title' => 'Account Approved',
            'message' => 'Your account has been approved by the administrator. You can now access all features.',
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', "User {$user->name} has been approved successfully.");
    }

    /**
     * Reject a user
     */
    public function reject(Request $request, User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $user->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        // Create notification for user rejection
        \App\Models\Notification::create([
            'type' => 'danger',
            'title' => 'Account Rejected',
            'message' => 'Your account has been rejected. Reason: ' . $request->rejection_reason,
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', "User {$user->name} has been rejected.");
    }

    /**
     * Delete a user permanently
     */
    public function destroy(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot delete admin users.');
        }

        $user->delete();
        return redirect()->back()->with('success', "User {$user->name} has been deleted permanently.");
    }

    /**
     * Show user details
     */
    public function show(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return view('auth.users.show', compact('user'));
    }

    /**
     * Toggle user active/inactive status
     */
    public function toggleStatus(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->isAdmin()) {
            return redirect()->back()->with('error', 'Cannot modify admin user status.');
        }

        // Toggle between approved and inactive
        $newStatus = $user->status === 'approved' ? 'inactive' : 'approved';
        $statusText = $newStatus === 'approved' ? 'activated' : 'deactivated';

        $user->update([
            'status' => $newStatus,
            'approved_by' => Auth::id(),
            'approved_at' => $newStatus === 'approved' ? now() : $user->approved_at,
        ]);

        // Create notification for user status change
        \App\Models\Notification::create([
            'type' => $newStatus === 'approved' ? 'success' : 'warning',
            'title' => 'Account Status Changed',
            'message' => "Your account has been {$statusText} by the administrator.",
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', "User {$user->name} has been {$statusText} successfully.");
    }

    /**
     * Reactivate a rejected user (move to pending)
     */
    public function reactivate(User $user)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        if ($user->status !== 'rejected') {
            return redirect()->back()->with('error', 'Only rejected users can be reactivated.');
        }

        $user->update([
            'status' => 'pending',
            'rejection_reason' => null,
            'approved_by' => null,
            'approved_at' => null,
        ]);

        // Create notification for user reactivation
        \App\Models\Notification::create([
            'type' => 'info',
            'title' => 'Account Reactivated',
            'message' => 'Your account has been reactivated and is now pending approval again.',
            'user_id' => $user->id,
            'is_read' => false,
        ]);

        return redirect()->back()->with('success', "User {$user->name} has been reactivated and moved to pending status.");
    }

    /**
     * Bulk approve multiple users
     */
    public function bulkApprove(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->user_ids)->where('status', 'pending')->get();
        $approvedCount = 0;

        foreach ($users as $user) {
            $user->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => Auth::id(),
                'rejection_reason' => null,
            ]);

            // Create notification for each user
            \App\Models\Notification::create([
                'type' => 'success',
                'title' => 'Account Approved',
                'message' => 'Your account has been approved by the administrator. You can now access all features.',
                'user_id' => $user->id,
                'is_read' => false,
            ]);

            $approvedCount++;
        }

        return redirect()->back()->with('success', "Successfully approved {$approvedCount} users.");
    }

    /**
     * Bulk reject multiple users
     */
    public function bulkReject(Request $request)
    {
        if (!Auth::check() || !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
            'rejection_reason' => 'required|string|max:500'
        ]);

        $users = User::whereIn('id', $request->user_ids)->where('status', 'pending')->get();
        $rejectedCount = 0;

        foreach ($users as $user) {
            $user->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'rejection_reason' => $request->rejection_reason,
            ]);

            // Create notification for each user
            \App\Models\Notification::create([
                'type' => 'danger',
                'title' => 'Account Rejected',
                'message' => 'Your account has been rejected. Reason: ' . $request->rejection_reason,
                'user_id' => $user->id,
                'is_read' => false,
            ]);

            $rejectedCount++;
        }

        return redirect()->back()->with('success', "Successfully rejected {$rejectedCount} users.");
    }
}
