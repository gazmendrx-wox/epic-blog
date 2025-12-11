<template>
  <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">Reset your password</h2>
        <p class="mt-2 text-gray-600">Enter your new password below</p>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ successMessage }}</p>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ errorMessage }}</p>
      </div>

      <!-- Reset Password Form -->
      <form @submit.prevent="handleSubmit" class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-lg">
        <div class="space-y-4">
          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              New Password
            </label>
            <input
              id="password"
              v-model="form.password"
              type="password"
              required
              :class="[
                'appearance-none relative block w-full px-3 py-2 border rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                hasError('password') ? 'border-red-500' : 'border-gray-300'
              ]"
              placeholder="••••••••"
            />
            <p v-if="hasError('password')" class="mt-1 text-sm text-red-600">{{ getFieldError('password') }}</p>
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">
              Confirm Password
            </label>
            <input
              id="password-confirm"
              v-model="form.password_confirmation"
              type="password"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="••••••••"
            />
          </div>
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            :disabled="loading"
            :class="[
              'w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition',
              loading ? 'bg-indigo-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
            ]"
          >
            <span v-if="loading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Resetting password...
            </span>
            <span v-else>Reset Password</span>
          </button>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            <NuxtLink to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
              Back to login
            </NuxtLink>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Reset Password - Epic Blog',
  meta: [
    { name: 'description', content: 'Reset your Epic Blog password' }
  ]
})

const { resetPassword } = useAuth()
const route = useRoute()
const router = useRouter()

// Get token and email from query params
const token = route.query.token as string
const email = route.query.email as string

// Form data
const form = ref({
  email: email || '',
  token: token || '',
  password: '',
  password_confirmation: '',
})

// UI state
const loading = ref(false)
const errorMessage = ref('')
const errors = ref<Record<string, string[]>>({})
const successMessage = ref('')

// Redirect if no token
if (!token || !email) {
  errorMessage.value = 'Invalid or missing reset token'
}

// Handle form submission
const handleSubmit = async () => {
  // Reset errors
  errorMessage.value = ''
  errors.value = {}
  successMessage.value = ''
  loading.value = true

  const result = await resetPassword(form.value)

  if (result.success) {
    successMessage.value = 'Password reset successful! Redirecting to login...'
    
    // Redirect to login after 2 seconds
    setTimeout(() => {
      router.push('/login')
    }, 2000)
  } else {
    errorMessage.value = result.error
    errors.value = result.errors
  }

  loading.value = false
}

// Get error message for a field
const getFieldError = (field: string): string => {
  return errors.value[field]?.[0] || ''
}

// Check if field has error
const hasError = (field: string): boolean => {
  return !!errors.value[field]
}
</script>
