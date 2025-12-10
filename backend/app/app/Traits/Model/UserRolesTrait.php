<?php

namespace App\Traits\Model;

trait UserRolesTrait
{
    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is an author.
     */
    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    /**
     * Check if user is a reader.
     */
    public function isReader(): bool
    {
        return $this->role === 'reader';
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles.
     */
    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->role, $roles);
    }
}
