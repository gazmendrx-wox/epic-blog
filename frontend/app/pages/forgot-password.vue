<template>
  <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">Forgot your password?</h2>
        <p class="mt-2 text-gray-600">Enter your email and we'll send you a reset link</p>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ successMessage }}</p>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ errorMessage }}</p>
      </div>

      <!-- Forgot Password Form -->
      <form @submit.prevent="handleSubmit" class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-lg">
        <div class="space-y-4">
          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email address
            </label>
            <input
              id="email"
              v-model="form.email"
              type="email"
              required
              :class="[
                'appearance-none relative block w-full px-3 py-2 border rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                hasError('email') ? 'border-red-500' : 'border-gray-300'
              ]"
              placeholder="you@example.com"
            />
            <p v-if="hasError('email')" class="mt-1 text-sm text-red-600">{{ getFieldError('email') }}</p>
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
              Sending reset link...
            </span>
            <span v-else>Send Reset Link</span>
          </button>
        </div>

        <!-- Divider -->
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or</span>
          </div>
        </div>

        <!-- Back to Login Link -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            Remember your password?
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
  title: 'Forgot Password - Epic Blog',
  meta: [
    { name: 'description', content: 'Reset your Epic Blog password' }
  ]
})

const { forgotPassword } = useAuth()

// Form data
const form = ref({
  email: '',
})

// UI state
const loading = ref(false)
const errorMessage = ref('')
const errors = ref<Record<string, string[]>>({})
const successMessage = ref('')

// Handle form submission
const handleSubmit = async () => {
  // Reset errors
  errorMessage.value = ''
  errors.value = {}
  successMessage.value = ''
  loading.value = true

  const result = await forgotPassword(form.value.email)

  if (result.success) {
    successMessage.value = 'Password reset link has been sent to your email!'
    form.value.email = ''
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
