export const useUsers = () => {
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
   * Get all users (admin only)
   */
  const getAllUsers = async (page: number = 1, perPage: number = 50, includeSuspended: boolean = true) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users?page=${page}&per_page=${perPage}&include_suspended=${includeSuspended}`, {
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
        error: error.data?.message || 'Failed to fetch users',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get a single user by ID (admin only)
   */
  const getUserById = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users/${id}`, {
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
        error: error.data?.message || 'Failed to fetch user',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Update user details (admin only)
   */
  const updateUser = async (id: number, data: { name?: string; email?: string; role?: string }) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users/${id}`, {
        method: 'PUT',
        headers: getAuthHeaders(),
        body: data,
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to update user',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Suspend a user (admin only)
   */
  const suspendUser = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users/${id}/suspend`, {
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
        error: error.data?.message || 'Failed to suspend user',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Unsuspend a user (admin only)
   */
  const unsuspendUser = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users/${id}/unsuspend`, {
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
        error: error.data?.message || 'Failed to unsuspend user',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Permanently delete a user (admin only)
   */
  const deleteUser = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users/${id}`, {
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
        error: error.data?.message || 'Failed to delete user',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get user statistics (admin only)
   */
  const getUserStats = async (id: number) => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/users/${id}/stats`, {
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
        error: error.data?.message || 'Failed to fetch user statistics',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Get platform statistics (admin only)
   */
  const getPlatformStats = async () => {
    try {
      const response = await $fetch(`${apiBase}/api/admin/stats`, {
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
        error: error.data?.message || 'Failed to fetch platform statistics',
        errors: error.data?.errors || {},
      }
    }
  }

  return {
    getAllUsers,
    getUserById,
    updateUser,
    suspendUser,
    unsuspendUser,
    deleteUser,
    getUserStats,
    getPlatformStats,
  }
}
