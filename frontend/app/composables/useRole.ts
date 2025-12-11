import type { User } from './useAuth'

export const useRole = () => {
  const { user } = useAuth()

  /**
   * Check if user is an admin
   */
  const isAdmin = computed(() => {
    return user.value?.role === 'admin'
  })

  /**
   * Check if user is an author
   */
  const isAuthor = computed(() => {
    return user.value?.role === 'author'
  })

  /**
   * Check if user is a reader
   */
  const isReader = computed(() => {
    return user.value?.role === 'reader'
  })

  /**
   * Check if user has a specific role
   */
  const hasRole = (role: string): boolean => {
    return user.value?.role === role
  }

  /**
   * Check if user has any of the specified roles
   */
  const hasAnyRole = (roles: string[]): boolean => {
    return roles.includes(user.value?.role || '')
  }

  /**
   * Check if user can create posts
   */
  const canCreatePosts = computed(() => {
    return isAdmin.value || isAuthor.value
  })

  /**
   * Check if user can approve posts
   */
  const canApprovePosts = computed(() => {
    return isAdmin.value
  })

  /**
   * Check if user can manage users
   */
  const canManageUsers = computed(() => {
    return isAdmin.value
  })

  return {
    isAdmin,
    isAuthor,
    isReader,
    hasRole,
    hasAnyRole,
    canCreatePosts,
    canApprovePosts,
    canManageUsers,
  }
}
