export const useLikes = () => {
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
   * Toggle like on a post (authenticated)
   */
  const toggleLike = async (postId: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${postId}/like`, {
        method: 'POST',
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      console.error('Toggle like error:', error)
      return {
        success: false,
        error: error.data?.message || 'Failed to toggle like',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get likes count for a post (public)
   */
  const getLikesCount = async (postId: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${postId}/likes/count`, {
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
        error: error.data?.message || 'Failed to get likes count',
      }
    }
  }

  /**
   * Check if current user liked a post (authenticated)
   */
  const checkIfLiked = async (postId: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${postId}/likes/check`, {
        method: 'GET',
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to check like status',
      }
    }
  }

  return {
    toggleLike,
    getLikesCount,
    checkIfLiked,
  }
}
