<?php

namespace App\Services;

use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthService
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return User
     * @throws ValidationException
     */
    public function register(array $data): User
    {
        // Create the user
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'] ?? 'reader', // Default to reader if not specified
        ]);

        return $user;
    }

    /**
     * Login a user and return the authenticated user with token.
     *
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        // Find user by email
        $user = User::where('email', $credentials['email'])->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        // Delete old tokens
        $user->tokens()->delete();

        // Create new token
        $token = $user->createToken('auth-token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token,
        ];
    }

    /**
     * Send password reset link to user's email.
     *
     * @param string $email
     * @return void
     */
    public function forgotPassword(string $email): void
    {
        // Find user
        $user = User::where('email', $email)->first();

        // Generate reset token
        $token = Str::random(64);

        // Delete existing tokens for this email
        DB::table('password_reset_tokens')
            ->where('email', $email)
            ->delete();

        // Store token in database
        DB::table('password_reset_tokens')->insert([
            'email' => $email,
            'token' => Hash::make($token),
            'created_at' => now(),
        ]);

        // Send email with reset link
        Mail::to($email)->send(new PasswordResetMail($user, $token));
    }

    /**
     * Reset user's password.
     *
     * @param array $data
     * @return void
     * @throws ValidationException
     */
    public function resetPassword(array $data): void
    {
        // Get password reset record
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->first();

        // Check if record exists
        if (!$resetRecord) {
            throw ValidationException::withMessages([
                'email' => ['Invalid or expired reset token.'],
            ]);
        }

        // Verify token
        if (!Hash::check($data['token'], $resetRecord->token)) {
            throw ValidationException::withMessages([
                'token' => ['Invalid or expired reset token.'],
            ]);
        }

        // Check if token is expired (24 hours)
        if (now()->diffInHours($resetRecord->created_at) > 24) {
            DB::table('password_reset_tokens')
                ->where('email', $data['email'])
                ->delete();

            throw ValidationException::withMessages([
                'token' => ['Reset token has expired.'],
            ]);
        }

        // Update user password
        $user = User::where('email', $data['email'])->first();
        $user->password = Hash::make($data['password']);
        $user->save();

        // Delete all tokens for this user
        $user->tokens()->delete();

        // Delete password reset record
        DB::table('password_reset_tokens')
            ->where('email', $data['email'])
            ->delete();
    }
}
