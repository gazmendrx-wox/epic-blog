<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Get all users with pagination (excluding soft-deleted/suspended)
     */
    public function getAllUsers(int $perPage = 50)
    {
        return User::orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get all users including suspended ones
     */
    public function getAllUsersWithSuspended(int $perPage = 50)
    {
        return User::withTrashed()->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Get only suspended users
     */
    public function getSuspendedUsers(int $perPage = 50)
    {
        return User::onlyTrashed()->orderBy('deleted_at', 'desc')->paginate($perPage);
    }

    /**
     * Get users by role with pagination
     */
    public function getUsersByRole(string $role, int $perPage = 50)
    {
        return User::where('role', $role)
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get a single user by ID
     */
    public function getUserById(int $id): ?User
    {
        return User::find($id);
    }

    /**
     * Get a single user by email
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    /**
     * Update user details (name, email, role)
     */
    public function updateUser(User $user, array $data): User
    {
        if (isset($data['name'])) {
            $user->name = $data['name'];
        }

        if (isset($data['email'])) {
            $user->email = $data['email'];
        }

        if (isset($data['role'])) {
            $user->role = $data['role'];
        }

        $user->save();

        return $user;
    }

    /**
     * Update user password
     */
    public function updatePassword(User $user, string $password): User
    {
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    /**
     * Suspend a user (soft delete)
     */
    public function suspendUser(User $user): bool
    {
        // Revoke all user's tokens
        $user->tokens()->delete();

        // Soft delete the user (suspension)
        return $user->delete();
    }

    /**
     * Unsuspend a user (restore from soft delete)
     */
    public function unsuspendUser(User $user): bool
    {
        return $user->restore();
    }

    /**
     * Permanently delete a user and all their posts
     */
    public function deleteUserPermanently(User $user): bool
    {
        // Delete all user's posts
        $user->posts()->delete();

        // Revoke all user's tokens
        $user->tokens()->delete();

        // Permanently delete the user
        return $user->forceDelete();
    }

    /**
     * Get user statistics
     */
    public function getUserStats(User $user): array
    {
        return [
            'total_posts' => $user->posts()->count(),
            'approved_posts' => $user->posts()->where('status', 'approved')->count(),
            'pending_posts' => $user->posts()->where('status', 'pending')->count(),
            'draft_posts' => $user->posts()->where('status', 'draft')->count(),
            'rejected_posts' => $user->posts()->where('status', 'rejected')->count(),
        ];
    }

    /**
     * Get platform statistics (admin only)
     */
    public function getPlatformStats(): array
    {
        return [
            'total_users' => User::count(),
            'admins' => User::where('role', 'admin')->count(),
            'authors' => User::where('role', 'author')->count(),
            'readers' => User::where('role', 'reader')->count(),
            'suspended_users' => User::onlyTrashed()->count(),
        ];
    }

    /**
     * Check if user is suspended
     */
    public function isSuspended(User $user): bool
    {
        return $user->trashed();
    }
}
