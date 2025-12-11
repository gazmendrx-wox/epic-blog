<template>
  <div class="newsletter-box bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-8 text-white">
    <div class="max-w-md mx-auto">
      <!-- Icon -->
      <div class="flex justify-center mb-4">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
        </svg>
      </div>

      <!-- Title & Description -->
      <h3 class="text-2xl font-bold text-center mb-2">Subscribe for New Posts</h3>
      <p class="text-purple-100 text-center mb-6">
        Get notified when we publish new articles. No spam, unsubscribe anytime!
      </p>

      <!-- Success Message -->
      <div v-if="showSuccess" class="bg-white text-green-600 rounded-md p-4 mb-4 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-semibold">Successfully subscribed!</span>
        <p class="text-sm text-gray-600 mt-1">Check your email for new posts.</p>
      </div>

      <!-- Error Message -->
      <div v-if="error" class="bg-red-100 text-red-700 rounded-md p-4 mb-4 text-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline-block mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <span class="font-semibold">{{ error }}</span>
      </div>

      <!-- Form -->
      <form v-if="!showSuccess" @submit.prevent="handleSubscribe" class="space-y-4">
        <div>
          <input
            v-model="email"
            type="email"
            placeholder="Enter your email address"
            required
            class="w-full px-4 py-3 rounded-md text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-300"
            :disabled="loading"
          />
        </div>
        
        <button
          type="submit"
          :disabled="loading || !email"
          class="w-full bg-white text-purple-600 font-semibold py-3 px-6 rounded-md hover:bg-purple-50 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
        >
          <span v-if="!loading">Subscribe Now</span>
          <span v-else class="flex items-center">
            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Subscribing...
          </span>
        </button>
      </form>

      <!-- Privacy Note -->
      <p class="text-xs text-purple-200 text-center mt-4">
        We respect your privacy. Your email will only be used for post notifications.
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
const { subscribe } = useNewsletter()

const email = ref('')
const loading = ref(false)
const error = ref('')
const showSuccess = ref(false)

const handleSubscribe = async () => {
  if (!email.value) return
  
  loading.value = true
  error.value = ''
  
  const result = await subscribe(email.value)
  
  loading.value = false
  
  if (result.success) {
    showSuccess.value = true
    email.value = ''
    
    // Hide success message after 5 seconds
    setTimeout(() => {
      showSuccess.value = false
    }, 5000)
  } else {
    error.value = result.error || 'Failed to subscribe'
  }
}
</script>
