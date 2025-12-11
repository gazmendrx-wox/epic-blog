<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostCommentRequest;
use App\Http\Requests\UpdatePostCommentRequest;
use App\Http\Resources\PostCommentResource;
use App\Models\Post;
use App\Models\PostComment;
use App\Services\PostCommentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostCommentController extends Controller
{
    public function __construct(
        private PostCommentService $commentService
    ) {}

    /**
     * Get all comments for a post (public)
     */
    public function index(Post $post): AnonymousResourceCollection
    {
        $perPage = request()->get('per_page', 20);
        $comments = $this->commentService->getPostComments($post, $perPage);
        
        return PostCommentResource::collection($comments);
    }

    /**
     * Create a new comment (authenticated)
     */
    public function store(StorePostCommentRequest $request, Post $post): JsonResponse
    {
        // Only allow comments on approved posts
        if ($post->status !== 'approved') {
            return response()->json([
                'message' => 'You can only comment on approved posts'
            ], 403);
        }

        $comment = $this->commentService->createComment(
            $post,
            auth()->user(),
            $request->input('content')
        );

        return response()->json([
            'message' => 'Comment created successfully',
            'data' => new PostCommentResource($comment),
        ], 201);
    }

    /**
     * Update a comment (authenticated - owner only)
     */
    public function update(UpdatePostCommentRequest $request, PostComment $comment): JsonResponse
    {
        if (!$this->commentService->canUpdateComment($comment, auth()->user())) {
            return response()->json([
                'message' => 'You are not authorized to update this comment'
            ], 403);
        }

        $updatedComment = $this->commentService->updateComment(
            $comment,
            $request->input('content')
        );

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => new PostCommentResource($updatedComment),
        ], 200);
    }

    /**
     * Delete a comment (authenticated - owner or admin)
     */
    public function destroy(PostComment $comment): JsonResponse
    {
        if (!$this->commentService->canDeleteComment($comment, auth()->user())) {
            return response()->json([
                'message' => 'You are not authorized to delete this comment'
            ], 403);
        }

        $this->commentService->deleteComment($comment);

        return response()->json([
            'message' => 'Comment deleted successfully',
        ], 200);
    }

    /**
     * Get comments count for a post (public)
     */
    public function count(Post $post): JsonResponse
    {
        $count = $this->commentService->getCommentsCount($post);

        return response()->json([
            'data' => [
                'comments_count' => $count,
            ],
        ], 200);
    }
}
