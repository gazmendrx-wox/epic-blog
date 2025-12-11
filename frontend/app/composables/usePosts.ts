export const usePosts = () => {
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
   * Get all approved posts (public)
   */
  const getAllPosts = async (page: number = 1, perPage: number = 50) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts?page=${page}&per_page=${perPage}`, {
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
        error: error.data?.message || 'Failed to fetch posts',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get a single post by slug
   */
  const getPostBySlug = async (slug: string) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${slug}`, {
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
        error: error.data?.message || 'Failed to fetch post',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get current user's posts
   */
  const getMyPosts = async (page: number = 1, perPage: number = 50) => {
    try {
      const response = await $fetch(`${apiBase}/api/my-posts?page=${page}&per_page=${perPage}`, {
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
        error: error.data?.message || 'Failed to fetch your posts',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Create a new post
   */
  const createPost = async (data: {
    title: string
    content: string
    excerpt?: string
    status?: string
  }) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts`, {
        method: 'POST',
        body: data,
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to create post',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Update an existing post
   */
  const updatePost = async (id: number, data: {
    title: string
    content: string
    excerpt?: string
    status?: string
  }) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${id}`, {
        method: 'PUT',
        body: data,
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to update post',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Delete a post
   */
  const deletePost = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${id}`, {
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
        error: error.data?.message || 'Failed to delete post',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Approve a post (admin only)
   */
  const approvePost = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${id}/approve`, {
        method: 'POST',
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to approve post',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Reject a post (admin only)
   */
  const rejectPost = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/posts/${id}/reject`, {
        method: 'POST',
        headers: getAuthHeaders(),
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to reject post',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get all posts for admin (admin only)
   */
  const getAdminPosts = async (page: number = 1, perPage: number = 50) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/posts?page=${page}&per_page=${perPage}`, {
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
        error: error.data?.message || 'Failed to fetch posts',
        errors: error.data?.errors || {},
      }
    }
  }

  return {
    getAllPosts,
    getPostBySlug,
    getMyPosts,
    createPost,
    updatePost,
    deletePost,
    approvePost,
    rejectPost,
    getAdminPosts,
  }
}
