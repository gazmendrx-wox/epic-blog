export const useComments = () => {
  const config = useRuntimeConfig()
  const apiBase = config.public.apiBase
  const { token } = useAuth()

  /**
   * Get authentication headers
   */
  const getAuthHeaders = () => {
    return {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'Authorization': `Bearer ${token.value}`,
    }
  }

  /**
   * Get all comments for a post (public)
   */
  const getPostComments = async (postId: number, page: number = 1, perPage: number = 20) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${postId}/comments?page=${page}&per_page=${perPage}`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
        },
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to fetch comments',
      }
    }
  }

  /**
   * Create a comment on a post (authenticated)
   */
  const createComment = async (postId: number, content: string) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${postId}/comments`, {
        method: 'POST',
        headers: getAuthHeaders(),
        body: {
          content,
        },
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      console.error('Create comment error:', error)
      return {
        success: false,
        error: error.data?.message || 'Failed to create comment',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Update a comment (authenticated - owner only)
   */
  const updateComment = async (commentId: number, content: string) => {
    try {
      const response = await $fetch(`${apiBase}/api/comments/${commentId}`, {
        method: 'PUT',
        headers: getAuthHeaders(),
        body: {
          content,
        },
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to update comment',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Delete a comment (authenticated - owner or admin)
   */
  const deleteComment = async (commentId: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/comments/${commentId}`, {
        method: 'DELETE',
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to delete comment',
      }
    }
  }

  /**
   * Get comments count for a post (public)
   */
  const getCommentsCount = async (postId: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${postId}/comments/count`, {
        method: 'GET',
        headers: {
          'Accept': 'application/json',
        },
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to get comments count',
      }
    }
  }

  return {
    getPostComments,
    createComment,
    updateComment,
    deleteComment,
    getCommentsCount,
  }
}
