<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">My Posts</h1>
        <p class="mt-2 text-gray-600">Manage your blog posts</p>
      </div>
      <NuxtLink 
        to="/posts/create"
        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
      >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Create New Post
      </NuxtLink>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
      <nav class="-mb-px flex space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeTab = tab.value"
          :class="[
            'py-4 px-1 border-b-2 font-medium text-sm transition',
            activeTab === tab.value
              ? 'border-indigo-500 text-indigo-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          {{ tab.label }}
          <span 
            :class="[
              'ml-2 py-0.5 px-2 rounded-full text-xs',
              activeTab === tab.value ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'
            ]"
          >
            {{ getPostCount(tab.value) }}
          </span>
        </button>
      </nav>
    </div>

    <!-- Posts List -->
    <div class="space-y-4">
      <!-- Empty State -->
      <div v-if="filteredPosts.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No posts</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new post.</p>
        <div class="mt-6">
          <NuxtLink 
            to="/posts/create"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Post
          </NuxtLink>
        </div>
      </div>

      <!-- Post Cards -->
      <div v-else class="bg-white shadow-sm rounded-lg divide-y divide-gray-200">
        <div 
          v-for="post in filteredPosts" 
          :key="post.id"
          class="p-6 hover:bg-gray-50 transition"
        >
          <div class="flex items-start justify-between">
            <div class="flex-1 min-w-0">
              <h3 class="text-lg font-semibold text-gray-900 mb-2">
                {{ post.title }}
              </h3>
              <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                {{ post.excerpt }}
              </p>
              <div class="flex items-center gap-4 text-sm text-gray-500">
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ post.date }}
                </span>
                <span :class="[
                  'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                  getStatusColor(post.status)
                ]">
                  {{ post.status }}
                </span>
              </div>
            </div>
            <div class="ml-4 flex-shrink-0 flex items-center gap-2">
              <button class="text-indigo-600 hover:text-indigo-700 p-2 rounded-md hover:bg-indigo-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
              <button class="text-gray-600 hover:text-gray-700 p-2 rounded-md hover:bg-gray-100 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button class="text-red-600 hover:text-red-700 p-2 rounded-md hover:bg-red-50 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

useHead({
  title: 'My Posts - Epic Blog',
  meta: [
    { name: 'description', content: 'Manage your blog posts' }
  ]
})

const { canCreatePosts } = useRole()
const router = useRouter()

// Redirect if user cannot create posts
if (!canCreatePosts.value) {
  router.push('/')
}

// Tabs
const tabs = [
  { label: 'All', value: 'all' },
  { label: 'Published', value: 'approved' },
  { label: 'Pending', value: 'pending' },
  { label: 'Draft', value: 'draft' },
  { label: 'Rejected', value: 'rejected' },
]

const activeTab = ref('all')

// Mock data - TODO: Replace with actual API call
const posts = ref([
  {
    id: 1,
    title: 'Getting Started with Vue 3 and TypeScript',
    excerpt: 'Learn how to set up a Vue 3 project with TypeScript and get the most out of the Composition API.',
    status: 'approved',
    date: 'Dec 10, 2025'
  },
  {
    id: 2,
    title: 'Building RESTful APIs with Laravel',
    excerpt: 'A comprehensive guide to creating robust and scalable APIs using Laravel framework.',
    status: 'pending',
    date: 'Dec 11, 2025'
  },
  {
    id: 3,
    title: 'Docker for Beginners',
    excerpt: 'Understanding containers and how to use Docker for development.',
    status: 'draft',
    date: 'Dec 09, 2025'
  },
])

// Computed filtered posts
const filteredPosts = computed(() => {
  if (activeTab.value === 'all') {
    return posts.value
  }
  return posts.value.filter(post => post.status === activeTab.value)
})

// Get post count for each tab
const getPostCount = (status: string) => {
  if (status === 'all') {
    return posts.value.length
  }
  return posts.value.filter(post => post.status === status).length
}

// Get status color
const getStatusColor = (status: string) => {
  const colors: Record<string, string> = {
    approved: 'bg-green-100 text-green-800',
    pending: 'bg-yellow-100 text-yellow-800',
    draft: 'bg-gray-100 text-gray-800',
    rejected: 'bg-red-100 text-red-800',
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}
</script>
