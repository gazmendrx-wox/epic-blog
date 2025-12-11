<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
      <!-- Loading State -->
      <div v-if="loading" class="text-center">
        <svg class="animate-spin h-12 w-12 text-indigo-600 mx-auto mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="text-gray-600">Processing your request...</p>
      </div>

      <!-- Success State -->
      <div v-else-if="success" class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mb-4">
          <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Unsubscribed Successfully</h2>
        <p class="text-gray-600 mb-6">
          You've been unsubscribed from our newsletter. We're sorry to see you go!
        </p>
        <p class="text-sm text-gray-500 mb-6">
          You won't receive any more email notifications about new posts.
        </p>
        <NuxtLink 
          to="/" 
          class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-semibold transition"
        >
          Back to Homepage
        </NuxtLink>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center">
        <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
          <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </div>
        <h2 class="text-2xl font-bold text-gray-900 mb-2">Unsubscribe Failed</h2>
        <p class="text-gray-600 mb-6">
          {{ error }}
        </p>
        <NuxtLink 
          to="/" 
          class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-md font-semibold transition"
        >
          Back to Homepage
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const { unsubscribe } = useNewsletter()

const loading = ref(true)
const success = ref(false)
const error = ref('')

onMounted(async () => {
  const token = route.params.token as string
  
  if (!token) {
    error.value = 'Invalid unsubscribe link. Please check your email and try again.'
    loading.value = false
    return
  }

  const result = await unsubscribe(token)
  
  loading.value = false
  
  if (result.success) {
    success.value = true
  } else {
    error.value = result.error || 'Failed to unsubscribe. The link may be invalid or expired.'
  }
})

useHead({
  title: 'Unsubscribe - Epic Blog',
  meta: [
    { name: 'description', content: 'Unsubscribe from Epic Blog newsletter' }
  ]
})
</script>
