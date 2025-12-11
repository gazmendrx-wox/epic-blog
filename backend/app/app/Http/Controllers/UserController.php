<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function __construct(
        private UserService $userService
    ) {}

    /**
     * Get all users (admin only)
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $perPage = $request->get('per_page', 50);
        $includeSuspended = $request->boolean('include_suspended', false);
        
        $users = $includeSuspended 
            ? $this->userService->getAllUsersWithSuspended($perPage)
            : $this->userService->getAllUsers($perPage);

        return UserResource::collection($users);
    }

    /**
     * Get a single user by ID (admin only)
     */
    public function show(int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return new UserResource($user);
    }

    /**
     * Update user details (admin only)
     */
    public function update(Request $request, int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Prevent admin from demoting themselves
        if ($user->id === auth()->id() && $request->has('role') && $request->role !== 'admin') {
            return response()->json([
                'message' => 'You cannot change your own role'
            ], 403);
        }

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $id,
            'role' => 'sometimes|in:admin,author,reader',
        ]);

        $updatedUser = $this->userService->updateUser($user, $request->only(['name', 'email', 'role']));

        return new UserResource($updatedUser);
    }

    /**
     * Update user password (admin only)
     */
    public function updatePassword(Request $request, int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $updatedUser = $this->userService->updatePassword($user, $request->password);

        return new UserResource($updatedUser);
    }

    /**
     * Suspend a user (admin only)
     */
    public function suspend(int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Prevent admin from suspending themselves
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot suspend yourself'
            ], 403);
        }

        $this->userService->suspendUser($user);

        // Refresh to get updated data with soft delete
        $user->refresh();

        return new UserResource($user);
    }

    /**
     * Unsuspend a user (admin only)
     */
    public function unsuspend(int $id): UserResource|JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            // Try to find suspended user
            $user = User::withTrashed()->find($id);
            
            if (!$user) {
                return response()->json(['message' => 'User not found'], 404);
            }
        }

        if (!$user->trashed()) {
            return response()->json([
                'message' => 'User is not suspended'
            ], 400);
        }

        $this->userService->unsuspendUser($user);

        // Refresh to get updated data
        $user->refresh();

        return new UserResource($user);
    }

    /**
     * Permanently delete a user (admin only)
     */
    public function destroy(int $id): JsonResponse
    {
        // Find user including soft deleted ones
        $user = User::withTrashed()->find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return response()->json([
                'message' => 'You cannot delete yourself'
            ], 403);
        }

        $this->userService->deleteUserPermanently($user);

        return response()->json([
            'message' => 'User permanently deleted successfully'
        ], 200);
    }

    /**
     * Get user statistics (admin only)
     */
    public function stats(int $id): JsonResponse
    {
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $stats = $this->userService->getUserStats($user);

        return response()->json([
            'data' => $stats
        ]);
    }

    /**
     * Get platform statistics (admin only)
     */
    public function platformStats(): JsonResponse
    {
        $stats = $this->userService->getPlatformStats();

        return response()->json([
            'data' => $stats
        ]);
    }
}
