export const useAuth = () => {
  const config = useRuntimeConfig()
  const router = useRouter()
  const apiBase = config.public.apiBase

  // Reactive state for auth
  const user = useState('user', () => null)
  const token = useState('token', () => null)
  const isAuthenticated = computed(() => !!token.value)

  /**
   * Register a new user
   */
  const register = async (data: {
    name: string
    email: string
    password: string
    password_confirmation: string
    role?: string
  }) => {
    try {
      const response = await $fetch(`${apiBase}/api/register`, {
        method: 'POST',
        body: data,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      })

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Registration failed',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Login user
   */
  const login = async (credentials: { email: string; password: string }) => {
    try {
      const response: any = await $fetch(`${apiBase}/api/login`, {
        method: 'POST',
        body: credentials,
        headers: {
          'Accept': 'application/json',
          'Content-Type': 'application/json',
        },
      })

      // Store user and token
      user.value = response.user
      token.value = response.token

      // Store token in localStorage
      if (process.client) {
        localStorage.setItem('auth_token', response.token)
        localStorage.setItem('user', JSON.stringify(response.user))
      }

      return {
        success: true,
        data: response,
      }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Login failed',
        errors: error.data?.errors || {},
      }
    }
  }

  /**
   * Logout user
   */
  const logout = () => {
    user.value = null
    token.value = null

    if (process.client) {
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user')
    }

    router.push('/login')
  }

  /**
   * Initialize auth from localStorage
   */
  const initAuth = () => {
    if (process.client) {
      const storedToken = localStorage.getItem('auth_token')
      const storedUser = localStorage.getItem('user')

      if (storedToken && storedUser) {
        token.value = storedToken
        user.value = JSON.parse(storedUser)
      }
    }
  }

  return {
    user,
    token,
    isAuthenticated,
    register,
    login,
    logout,
    initAuth,
  }
}
