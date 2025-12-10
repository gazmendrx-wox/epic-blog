<template>
  <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">Create your account</h2>
        <p class="mt-2 text-gray-600">Join our community of writers and readers</p>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ successMessage }}</p>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ errorMessage }}</p>
      </div>

      <!-- Registration Form -->
      <form @submit.prevent="handleSubmit" class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-lg">
        <div class="space-y-4">
          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              Full name
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              required
              :class="[
                'appearance-none relative block w-full px-3 py-2 border rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent',
                hasError('name') ? 'border-red-500' : 'border-gray-300'
              ]"
              placeholder="John Doe"
            />
            <p v-if="hasError('name')" class="mt-1 text-sm text-red-600">{{ getFieldError('name') }}</p>
          </div>

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

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Password
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
              Confirm password
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

          <!-- Role Selection -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              I want to join as
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none">
                <input v-model="form.role" type="radio" name="role" value="reader" class="sr-only" />
                <div class="flex w-full items-center justify-between">
                  <div class="flex items-center">
                    <div class="text-sm">
                      <p class="font-medium text-gray-900">👤 Reader</p>
                      <p class="text-gray-500 text-xs">Browse & comment</p>
                    </div>
                  </div>
                </div>
              </label>
              <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none">
                <input v-model="form.role" type="radio" name="role" value="author" class="sr-only" />
                <div class="flex w-full items-center justify-between">
                  <div class="flex items-center">
                    <div class="text-sm">
                      <p class="font-medium text-gray-900">✍️ Author</p>
                      <p class="text-gray-500 text-xs">Write posts</p>
                    </div>
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="flex items-start">
          <input
            id="terms"
            type="checkbox"
            required
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer mt-0.5"
          />
          <label for="terms" class="ml-2 block text-sm text-gray-700 cursor-pointer">
            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
          </label>
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
              Creating account...
            </span>
            <span v-else>Create account</span>
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

        <!-- Login Link -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <NuxtLink to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
              Sign in
            </NuxtLink>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Sign Up - Epic Blog',
  meta: [
    { name: 'description', content: 'Create your Epic Blog account and join our community' }
  ]
})

const { register } = useAuth()
const router = useRouter()

// Form data
const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'reader',
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

  try {
    const result = await register(form.value)

    if (result.success) {
      successMessage.value = 'Registration successful! Redirecting to login...'
      form.value = {
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
        role: 'reader',
      }
      
      // Redirect to login after 2 seconds
      setTimeout(() => {
        router.push('/login')
      }, 2000)
    } else {
      errorMessage.value = result.error || 'Registration failed'
      errors.value = result.errors || {}
    }
  } catch (error: any) {
    errorMessage.value = 'An unexpected error occurred. Please try again.'
  } finally {
    loading.value = false
  }
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
