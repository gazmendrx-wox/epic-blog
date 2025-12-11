<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <NuxtLink to="/" class="flex items-center space-x-2">
              <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
              </svg>
              <span class="text-2xl font-bold text-gray-900">Epic Blog</span>
            </NuxtLink>
          </div>
          
          <div class="flex items-center space-x-4">
            <NuxtLink to="/" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
              Home
            </NuxtLink>
            <NuxtLink to="/about" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
              About
            </NuxtLink>
            
            <!-- Authenticated User Menu -->
            <template v-if="isAuthenticated">
              <!-- Posts Dropdown for Authors and Admins -->
              <div v-if="canCreatePosts" class="relative" @mouseleave="closeDropdown">
                <button 
                  @click="toggleDropdown"
                  @mouseenter="openDropdown"
                  class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition flex items-center"
                >
                  Posts
                  <svg class="w-4 h-4 ml-1" :class="{ 'rotate-180': showPostsDropdown }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                  </svg>
                </button>
                
                <!-- Posts Dropdown Menu -->
                <div 
                  v-if="showPostsDropdown" 
                  @mouseenter="openDropdown"
                  class="absolute left-0 mt-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                >
                  <div class="py-1">
                    <NuxtLink 
                      to="/posts/create" 
                      @click="closeDropdown"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
                    >
                      Create Post
                    </NuxtLink>
                    <NuxtLink 
                      to="/my-posts" 
                      @click="closeDropdown"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
                    >
                      My Posts
                    </NuxtLink>
                    <NuxtLink 
                      v-if="isAdmin" 
                      to="/admin/posts" 
                      @click="closeDropdown"
                      class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 hover:text-indigo-600 transition"
                    >
                      Manage Submitted Posts
                    </NuxtLink>
                  </div>
                </div>
              </div>
              
              <!-- Users Link for Admins only -->
              <NuxtLink 
                v-if="isAdmin" 
                to="/admin/users" 
                class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition"
              >
                Users
              </NuxtLink>
              
              <span class="text-gray-700 px-3 py-2 text-sm">
                Welcome, <span class="font-medium text-indigo-600">{{ user?.name }}</span>
              </span>
              <button 
                @click="handleLogout"
                class="text-gray-700 hover:text-red-600 px-3 py-2 rounded-md text-sm font-medium transition"
              >
                Logout
              </button>
            </template>
            
            <!-- Guest Menu -->
            <template v-else>
              <NuxtLink to="/login" class="text-gray-700 hover:text-indigo-600 px-3 py-2 rounded-md text-sm font-medium transition">
                Login
              </NuxtLink>
              <NuxtLink to="/register" class="bg-indigo-600 text-white hover:bg-indigo-700 px-4 py-2 rounded-md text-sm font-medium transition">
                Sign Up
              </NuxtLink>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- Footer -->
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
            <form class="flex">
              <input type="email" placeholder="Your email" class="flex-1 px-4 py-2 rounded-l-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500" />
              <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 px-4 py-2 rounded-r-md transition">
                Subscribe
              </button>
            </form>
          </div>
        </div>
        <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
          <p>&copy; 2025 Epic Blog. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup lang="ts">
const { user, isAuthenticated, logout, initAuth } = useAuth()
const { isAdmin, isAuthor, canCreatePosts } = useRole()

// Dropdown state
const showPostsDropdown = ref(false)
let closeTimeout: NodeJS.Timeout | null = null

// Open dropdown
const openDropdown = () => {
  if (closeTimeout) {
    clearTimeout(closeTimeout)
    closeTimeout = null
  }
  showPostsDropdown.value = true
}

// Toggle dropdown on click
const toggleDropdown = () => {
  showPostsDropdown.value = !showPostsDropdown.value
}

// Close dropdown with delay
const closeDropdown = () => {
  closeTimeout = setTimeout(() => {
    showPostsDropdown.value = false
  }, 300)
}

// Initialize auth state from localStorage on mount
onMounted(() => {
  initAuth()
})

// Handle logout
const handleLogout = () => {
  logout()
}
</script>
