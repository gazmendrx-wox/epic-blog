<template>
  <footer class="bg-gray-800 text-white mt-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <div>
          <h3 class="text-lg font-semibold mb-4">Epic Blog</h3>
          <p class="text-gray-400">A modern blogging platform built with Laravel and Nuxt.js</p>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
          <ul class="space-y-2">
            <li><NuxtLink to="/" class="text-gray-400 hover:text-white transition">Home</NuxtLink></li>
            <li><NuxtLink to="/about" class="text-gray-400 hover:text-white transition">About</NuxtLink></li>
            <li><NuxtLink to="/login" class="text-gray-400 hover:text-white transition">Login</NuxtLink></li>
          </ul>
        </div>
        <div>
          <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
          <p class="text-gray-400 mb-4">Subscribe to get the latest posts</p>
          <form @submit.prevent="handleSubscribe" class="flex">
            <input 
              v-model="email" 
              type="email" 
              placeholder="Your email" 
              required
              class="flex-1 px-4 py-2 rounded-l-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500" 
            />
            <button 
              type="submit" 
              :disabled="subscribing"
              class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-r-md transition disabled:opacity-50"
            >
              {{ subscribing ? 'Subscribing...' : 'Subscribe' }}
            </button>
          </form>
          <p v-if="subscribeMessage" :class="[
            'mt-2 text-sm',
            subscribeSuccess ? 'text-green-400' : 'text-red-400'
          ]">
            {{ subscribeMessage }}
          </p>
        </div>
      </div>
      <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
        <p>&copy; {{ currentYear }} Epic Blog. All rights reserved.</p>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const { subscribe } = useNewsletter()

const email = ref('')
const subscribing = ref(false)
const subscribeMessage = ref('')
const subscribeSuccess = ref(false)
const currentYear = new Date().getFullYear()

const handleSubscribe = async () => {
  if (!email.value) return
  
  subscribing.value = true
  subscribeMessage.value = ''
  
  const result = await subscribe(email.value)
  
  subscribing.value = false
  
  if (result.success) {
    subscribeSuccess.value = true
    subscribeMessage.value = 'Successfully subscribed! Check your email for new posts.'
    email.value = ''
  } else {
    subscribeSuccess.value = false
    subscribeMessage.value = result.error || 'Failed to subscribe. Please try again.'
  }
  
  setTimeout(() => {
    subscribeMessage.value = ''
  }, 5000)
}
</script>
