<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Loading State -->
    <div v-if="loadingPost" class="text-center py-12">
      <svg class="animate-spin h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <p class="mt-4 text-gray-600">Loading post...</p>
    </div>

    <!-- Error Loading Post -->
    <div v-else-if="loadError" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading post</h3>
      <p class="mt-1 text-sm text-red-600">{{ loadError }}</p>
      <button 
        @click="router.push('/my-posts')"
        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
      >
        Back to My Posts
      </button>
    </div>

    <!-- Edit Form -->
    <template v-else>
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Edit Post</h1>
        <p class="mt-2 text-gray-600">Update your post content</p>
      </div>

      <!-- Success Message -->
      <div v-if="successMessage" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ successMessage }}</p>
      </div>

      <!-- Error Message -->
      <div v-if="errorMessage" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
        <p class="font-medium">{{ errorMessage }}</p>
      </div>

      <!-- Edit Post Form -->
      <form @submit.prevent="handleSubmit" class="bg-white rounded-lg shadow-lg p-8 space-y-6">
        <!-- Post Status Badge -->
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-600">Current Status:</span>
          <span :class="[
            'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
            getStatusColor(post.status)
          ]">
            {{ post.status }}
          </span>
        </div>

        <!-- Title Field -->
        <div>
          <label for="title" class="block text-sm font-medium text-gray-700 mb-1">
            Post Title <span class="text-red-500">*</span>
          </label>
          <input
            id="title"
            v-model="form.title"
            type="text"
            required
            :class="[
              'appearance-none relative block w-full px-4 py-3 border rounded-md placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent text-lg',
              hasError('title') ? 'border-red-500' : 'border-gray-300'
            ]"
            placeholder="Enter a compelling title..."
          />
          <p v-if="hasError('title')" class="mt-1 text-sm text-red-600">{{ getFieldError('title') }}</p>
        </div>

        <!-- Excerpt Field -->
        <div>
          <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">
            Excerpt <span class="text-gray-500 text-xs">(Optional - will be auto-generated if empty)</span>
          </label>
          <textarea
            id="excerpt"
            v-model="form.excerpt"
            rows="2"
            :class="[
              'appearance-none relative block w-full px-4 py-3 border rounded-md placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none',
              hasError('excerpt') ? 'border-red-500' : 'border-gray-300'
            ]"
            placeholder="Brief summary of your post (max 500 characters)..."
            maxlength="500"
          ></textarea>
          <div class="mt-1 flex justify-between items-center">
            <p v-if="hasError('excerpt')" class="text-sm text-red-600">{{ getFieldError('excerpt') }}</p>
            <p class="text-xs text-gray-500 ml-auto">{{ form.excerpt?.length || 0 }}/500</p>
          </div>
        </div>

        <!-- Content Field -->
        <div>
          <label for="content" class="block text-sm font-medium text-gray-700 mb-1">
            Content <span class="text-red-500">*</span>
          </label>
          <textarea
            id="content"
            v-model="form.content"
            rows="16"
            required
            :class="[
              'appearance-none relative block w-full px-4 py-3 border rounded-md placeholder-gray-400 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-y',
              hasError('content') ? 'border-red-500' : 'border-gray-300'
            ]"
            placeholder="Write your post content here... (Markdown supported)"
          ></textarea>
          <p v-if="hasError('content')" class="mt-1 text-sm text-red-600">{{ getFieldError('content') }}</p>
          <p class="mt-1 text-xs text-gray-500">Tip: You can use Markdown formatting</p>
        </div>

        <!-- Status Warning for Approved Posts -->
        <div v-if="post.status === 'approved' && !isAdmin" class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
          <div class="flex items-start">
            <svg class="w-5 h-5 text-yellow-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>
            <div>
              <p class="text-sm font-medium text-yellow-900">Re-approval Required</p>
              <p class="text-sm text-yellow-700 mt-1">Editing this approved post will change its status to pending and require admin re-approval.</p>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
          <NuxtLink 
            to="/my-posts" 
            class="text-gray-700 hover:text-gray-900 px-4 py-2 rounded-md text-sm font-medium transition"
          >
            Cancel
          </NuxtLink>
          
          <button
            type="submit"
            :disabled="loading"
            :class="[
              'px-6 py-3 border border-transparent rounded-md shadow-sm text-sm font-medium text-white transition',
              loading ? 'bg-indigo-400 cursor-not-allowed' : 'bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500'
            ]"
          >
            <span v-if="loading" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Updating...
            </span>
            <span v-else>Update Post</span>
          </button>
        </div>
      </form>
    </template>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

useHead({
  title: 'Edit Post - Epic Blog',
  meta: [
    { name: 'description', content: 'Edit your blog post' }
  ]
})

const route = useRoute()
const router = useRouter()
const { isAdmin, canCreatePosts } = useRole()
const { updatePost } = usePosts()

// Redirect if user cannot create posts
if (!canCreatePosts.value) {
  router.push('/')
}

const postId = route.params.id as string

// Post data
const post = ref<any>({})
const loadingPost = ref(true)
const loadError = ref('')

// Form data
const form = ref({
  title: '',
  excerpt: '',
  content: '',
})

// UI state
const loading = ref(false)
const errorMessage = ref('')
const errors = ref<Record<string, string[]>>({})
const successMessage = ref('')

// Fetch post data
const fetchPost = async () => {
  loadingPost.value = true
  loadError.value = ''

  try {
    // TODO: Replace with getPostById from composable when available
    // For now, we'll use a workaround via getMyPosts
    const { getMyPosts } = usePosts()
    const result = await getMyPosts()

    if (result.success) {
      const posts = result.data.data || []
      const foundPost = posts.find((p: any) => p.id === parseInt(postId))

      if (foundPost) {
        post.value = foundPost
        form.value = {
          title: foundPost.title,
          excerpt: foundPost.excerpt || '',
          content: foundPost.content,
        }
      } else {
        loadError.value = 'Post not found or you do not have permission to edit it'
      }
    } else {
      loadError.value = result.error
    }
  } catch (error: any) {
    loadError.value = 'Failed to load post'
  } finally {
    loadingPost.value = false
  }
}

// Handle form submission
const handleSubmit = async () => {
  errorMessage.value = ''
  errors.value = {}
  successMessage.value = ''
  loading.value = true

  const result = await updatePost(parseInt(postId), form.value)

  if (result.success) {
    successMessage.value = 'Post updated successfully!'
    
    // Redirect after success
    setTimeout(() => {
      router.push('/my-posts')
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

// Fetch post on mount
onMounted(() => {
  fetchPost()
})
</script>
