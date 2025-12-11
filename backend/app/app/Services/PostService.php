<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;

class PostService
{
    /**
     * Create a new post
     */
    public function createPost(array $data, User $user): Post
    {
        $post = new Post();
        $post->user_id = $user->id;
        $post->title = $data['title'];
        $post->slug = $this->generateUniqueSlug($data['title']);
        $post->content = $data['content'];
        $post->excerpt = $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 150);
        
        // Auto-approve for admins, pending for authors
        if ($user->isAdmin()) {
            $post->status = 'approved';
            $post->approved_by = $user->id;
            $post->approved_at = now();
            $post->published_at = now();
        } else {
            $post->status = 'pending';
        }
        
        $post->save();
        
        return $post->fresh(['user', 'approver']);
    }

    /**
     * Update an existing post
     */
    public function updatePost(Post $post, array $data, User $user): Post
    {
        $post->title = $data['title'];
        $post->slug = $this->generateUniqueSlug($data['title'], $post->id);
        $post->content = $data['content'];
        $post->excerpt = $data['excerpt'] ?? Str::limit(strip_tags($data['content']), 150);
        
        // If admin is updating, they can change status
        if ($user->isAdmin() && isset($data['status'])) {
            $post->status = $data['status'];
            
            if ($data['status'] === 'approved' && !$post->approved_at) {
                $post->approved_by = $user->id;
                $post->approved_at = now();
                $post->published_at = now();
            }
        } else {
            // Authors updating their post resets to pending (unless already approved)
            if ($post->status === 'draft' || $post->status === 'rejected') {
                $post->status = 'pending';
            }
        }
        
        $post->save();
        
        return $post->fresh(['user', 'approver']);
    }

    /**
     * Delete a post
     */
    public function deletePost(Post $post): bool
    {
        return $post->delete();
    }

    /**
     * Approve a post (admin only)
     */
    public function approvePost(Post $post, User $admin): Post
    {
        $post->status = 'approved';
        $post->approved_by = $admin->id;
        $post->approved_at = now();
        $post->published_at = now();
        $post->save();
        
        // TODO: Dispatch newsletter job when implemented
        // SendPostNotificationJob::dispatch($post);
        
        return $post->fresh(['user', 'approver']);
    }

    /**
     * Reject a post (admin only)
     */
    public function rejectPost(Post $post, User $admin): Post
    {
        $post->status = 'rejected';
        $post->save();
        
        return $post->fresh(['user', 'approver']);
    }

    /**
     * Get all approved posts (public)
     */
    public function getAllApprovedPosts(): Collection
    {
        return Post::where('status', 'approved')
            ->with(['user'])
            ->orderBy('published_at', 'desc')
            ->get();
    }

    /**
     * Get a single post by slug
     */
    public function getPostBySlug(string $slug): ?Post
    {
        return Post::where('slug', $slug)
            ->with(['user', 'approver'])
            ->first();
    }

    /**
     * Get all posts for admin
     */
    public function getAllPostsForAdmin(): Collection
    {
        return Post::with(['user', 'approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get user's own posts
     */
    public function getUserPosts(User $user): Collection
    {
        return Post::where('user_id', $user->id)
            ->with(['approver'])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Generate a unique slug for the post
     */
    private function generateUniqueSlug(string $title, ?int $excludeId = null): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (true) {
            $query = Post::where('slug', $slug);
            
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
            
            if (!$query->exists()) {
                break;
            }
            
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
