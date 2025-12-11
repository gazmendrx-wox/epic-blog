<template>
  <div>
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
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <svg class="animate-spin h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600">{{ loadingMessage }}</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading posts</h3>
        <p class="mt-1 text-sm text-red-600">{{ error }}</p>
        <button 
          @click="$emit('retry')"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Try Again
        </button>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredPosts.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No posts</h3>
        <p class="mt-1 text-sm text-gray-500">{{ emptyMessage }}</p>
        <div v-if="showCreateButton" class="mt-6">
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
                {{ post.excerpt || 'No excerpt available' }}
              </p>
              <div class="flex items-center gap-4 text-sm text-gray-500">
                <span v-if="showAuthor && post.user" class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                  </svg>
                  {{ post.user.name }}
                </span>
                <span class="flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  {{ formatDate(post.created_at) }}
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
              <!-- View Button -->
              <NuxtLink 
                :to="`/posts/${post.slug}`"
                class="text-indigo-600 hover:text-indigo-700 p-2 rounded-md hover:bg-indigo-50 transition"
                title="View post"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </NuxtLink>

              <!-- Edit Button (Author only) -->
              <NuxtLink 
                v-if="showEditButton"
                :to="`/posts/${post.id}/edit`"
                class="text-gray-600 hover:text-gray-700 p-2 rounded-md hover:bg-gray-100 transition"
                title="Edit post"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </NuxtLink>

              <!-- Approve Button (Admin only) -->
              <button 
                v-if="showApproveButton && post.status === 'pending'"
                @click="$emit('approve', post.id)"
                class="text-green-600 hover:text-green-700 p-2 rounded-md hover:bg-green-50 transition"
                title="Approve post"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>

              <!-- Reject Button (Admin only) -->
              <button 
                v-if="showRejectButton && post.status === 'pending'"
                @click="$emit('reject', post.id)"
                class="text-orange-600 hover:text-orange-700 p-2 rounded-md hover:bg-orange-50 transition"
                title="Reject post"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>

              <!-- Delete Button -->
              <button 
                @click="$emit('delete', post.id)"
                class="text-red-600 hover:text-red-700 p-2 rounded-md hover:bg-red-50 transition"
                title="Delete post"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <Pagination 
      v-if="pagination && !loading && !error"
      :pagination="pagination"
      @page-change="$emit('page-change', $event)"
    />
  </div>
</template>

<script setup lang="ts">
interface Post {
  id: number
  title: string
  slug: string
  excerpt: string
  status: string
  created_at: string
  user?: {
    name: string
  }
}

interface Pagination {
  current_page: number
  last_page: number
  from: number
  to: number
  total: number
  per_page: number
}

interface Props {
  posts: Post[]
  pagination?: Pagination | null
  loading?: boolean
  error?: string
  loadingMessage?: string
  emptyMessage?: string
  showAuthor?: boolean
  showEditButton?: boolean
  showApproveButton?: boolean
  showRejectButton?: boolean
  showCreateButton?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  pagination: null,
  loading: false,
  error: '',
  loadingMessage: 'Loading posts...',
  emptyMessage: 'No posts found.',
  showAuthor: false,
  showEditButton: false,
  showApproveButton: false,
  showRejectButton: false,
  showCreateButton: false,
})

defineEmits<{
  delete: [postId: number]
  approve: [postId: number]
  reject: [postId: number]
  retry: []
  'page-change': [page: number]
}>()

// Tabs
const tabs = [
  { label: 'All', value: 'all' },
  { label: 'Published', value: 'approved' },
  { label: 'Pending', value: 'pending' },
  { label: 'Draft', value: 'draft' },
  { label: 'Rejected', value: 'rejected' },
]

const activeTab = ref('all')

// Computed filtered posts
const filteredPosts = computed(() => {
  if (activeTab.value === 'all') {
    return props.posts
  }
  return props.posts.filter(post => post.status === activeTab.value)
})

// Get post count for each tab
const getPostCount = (status: string) => {
  if (status === 'all') {
    return props.posts.length
  }
  return props.posts.filter(post => post.status === status).length
}

// Format date
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
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
