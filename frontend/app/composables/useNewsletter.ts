export const useNewsletter = () => {
  const config = useRuntimeConfig()
  const baseURL = config.public.apiBase

  const subscribe = async (email: string) => {
    try {
      const response = await $fetch(`${baseURL}/api/newsletter/subscribe`, {
        method: 'POST',
        body: { email }
      })
      return { success: true, data: response }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to subscribe to newsletter'
      }
    }
  }

  const unsubscribe = async (token: string) => {
    try {
      const response = await $fetch(`${baseURL}/api/newsletter/unsubscribe/${token}`, {
        method: 'GET'
      })
      return { success: true, data: response }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to unsubscribe from newsletter'
      }
    }
  }

  const getSubscriberCount = async () => {
    const { getToken } = useAuth()
    const token = getToken()

    try {
      const response = await $fetch(`${baseURL}/api/newsletter/count`, {
        method: 'GET',
        headers: {
          Authorization: `Bearer ${token}`
        }
      })
      return { success: true, data: response }
    } catch (error: any) {
      return {
        success: false,
        error: error.data?.message || 'Failed to get subscriber count'
      }
    }
  }

  return {
    subscribe,
    unsubscribe,
    getSubscriberCount
  }
}
