<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Welcome to Epic Blog! 👋
          </h1>
          <p class="text-xl md:text-2xl text-indigo-100 mb-8">
            Discover amazing stories, tutorials, and insights from our community
          </p>
          <div class="flex justify-center space-x-4">
            <NuxtLink 
              v-if="!isAuthenticated"
              to="/register" 
              class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition shadow-lg"
            >
              Get Started
            </NuxtLink>
            <NuxtLink 
              to="#posts" 
              class="bg-indigo-700 hover:bg-indigo-800 px-8 py-3 rounded-lg font-semibold transition border-2 border-white"
            >
              Explore Posts
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-12 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">{{ totalPosts }}</div>
            <div class="text-gray-600">Published Posts</div>
          </div>
          <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">{{ totalAuthors }}</div>
            <div class="text-gray-600">Active Authors</div>
          </div>
          <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">{{ totalLikes }}</div>
            <div class="text-gray-600">Total Likes</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Posts Section -->
    <section id="posts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Latest Posts</h2>
        <p class="text-gray-600">Check out our most recent articles and tutorials</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <svg class="animate-spin h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600">Loading posts...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading posts</h3>
        <p class="mt-1 text-sm text-red-600">{{ error }}</p>
        <button 
          @click="fetchPosts(currentPage)"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Try Again
        </button>
      </div>

      <!-- Posts Grid -->
      <div v-else-if="posts.length > 0" class="space-y-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <PostCard 
            v-for="post in posts" 
            :key="post.id" 
            :post="post"
            @liked="handlePostLiked"
            @unliked="handlePostUnliked"
          />
        </div>

        <!-- Pagination -->
        <Pagination 
          v-if="pagination"
          :pagination="pagination"
          @page-change="handlePageChange"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No posts yet</h3>
        <p class="mt-1 text-sm text-gray-500">Be the first to create a post!</p>
        <NuxtLink 
          v-if="isAuthenticated && !isReader"
          to="/posts/create"
          class="mt-6 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Create Post
        </NuxtLink>
      </div>
    </section>

    <!-- Newsletter Section -->
    <section class="py-16 bg-gray-50">
      <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <NewsletterBox />
      </div>
    </section>

    <!-- CTA Section -->
    <section v-if="!isAuthenticated" class="bg-gray-900 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Share Your Story?</h2>
        <p class="text-gray-300 mb-8 text-lg">Join our community of writers and start publishing today</p>
        <NuxtLink 
          to="/register" 
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold transition inline-block"
        >
          Create Your Account
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
const { isAuthenticated } = useAuth()
const { isReader } = useRole()
const { getAllPosts } = usePosts()

const posts = ref<any[]>([])
const pagination = ref<any>(null)
const currentPage = ref(1)
const loading = ref(false)
const error = ref('')

// Computed stats from posts
const totalPosts = computed(() => pagination.value?.total || 0)
const totalAuthors = computed(() => {
  const uniqueAuthors = new Set(posts.value.map(post => post.user?.id).filter(Boolean))
  return uniqueAuthors.size
})
const totalLikes = computed(() => {
  return posts.value.reduce((sum, post) => sum + (post.likes_count || 0), 0)
})

// Fetch posts
const fetchPosts = async (page: number = 1) => {
  loading.value = true
  error.value = ''

  const result = await getAllPosts(page, 12)

  if (result.success) {
    posts.value = result.data.data
    pagination.value = result.data.meta
    currentPage.value = page
  } else {
    error.value = result.error
  }

  loading.value = false
}

// Handle page change
const handlePageChange = (page: number) => {
  fetchPosts(page)
  // Scroll to posts section
  const postsSection = document.getElementById('posts')
  if (postsSection) {
    postsSection.scrollIntoView({ behavior: 'smooth' })
  }
}

// Handle post liked
const handlePostLiked = () => {
  // Refetch posts to update stats
  fetchPosts(currentPage.value)
}

// Handle post unliked
const handlePostUnliked = () => {
  // Refetch posts to update stats
  fetchPosts(currentPage.value)
}

// Fetch posts on mount
onMounted(() => {
  fetchPosts()
})

// Set page meta
useHead({
  title: 'Home - Epic Blog',
  meta: [
    { name: 'description', content: 'Welcome to Epic Blog - A modern blogging platform built with Laravel and Nuxt.js' }
  ]
})
</script>
