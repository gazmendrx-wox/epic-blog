<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Create New Post</h1>
      <p class="mt-2 text-gray-600">Share your thoughts with the community</p>
    </div>

    <!-- Success Message -->
    <div v-if="successMessage" class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
      <p class="font-medium">{{ successMessage }}</p>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
      <p class="font-medium">{{ errorMessage }}</p>
    </div>

    <!-- Create Post Form -->
    <form @submit.prevent="handleSubmit" class="bg-white rounded-lg shadow-lg p-8 space-y-6">
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
          <p class="text-xs text-gray-500 ml-auto">{{ form.excerpt.length }}/500</p>
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

      <!-- Status Info for Authors -->
      <div v-if="isAuthor" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
        <div class="flex items-start">
          <svg class="w-5 h-5 text-blue-600 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
          </svg>
          <div>
            <p class="text-sm font-medium text-blue-900">Post Approval Required</p>
            <p class="text-sm text-blue-700 mt-1">Your post will be submitted for admin approval before being published.</p>
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
        
        <div class="flex gap-3">
          <button
            type="button"
            @click="saveDraft"
            :disabled="loading"
            class="px-6 py-3 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition disabled:opacity-50 disabled:cursor-not-allowed"
          >
            Save as Draft
          </button>
          
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
              Publishing...
            </span>
            <span v-else>{{ isAdmin ? 'Publish Post' : 'Submit for Approval' }}</span>
          </button>
        </div>
      </div>
    </form>

    <!-- Writing Tips -->
    <div class="mt-8 bg-gray-50 rounded-lg p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-3">Writing Tips</h3>
      <ul class="space-y-2 text-sm text-gray-600">
        <li class="flex items-start">
          <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span>Use a clear and descriptive title that captures the essence of your post</span>
        </li>
        <li class="flex items-start">
          <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span>Break your content into paragraphs for better readability</span>
        </li>
        <li class="flex items-start">
          <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span>Proofread your content before submitting</span>
        </li>
        <li class="flex items-start">
          <svg class="w-5 h-5 text-indigo-600 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
          </svg>
          <span>Add an excerpt to help readers decide if they want to read more</span>
        </li>
      </ul>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})

useHead({
  title: 'Create Post - Epic Blog',
  meta: [
    { name: 'description', content: 'Create a new blog post' }
  ]
})

const router = useRouter()
const { isAdmin, isAuthor, canCreatePosts } = useRole()

// Redirect if user cannot create posts
if (!canCreatePosts.value) {
  router.push('/')
}

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

// Handle form submission (publish)
const handleSubmit = async () => {
  errorMessage.value = ''
  errors.value = {}
  successMessage.value = ''
  loading.value = true

  try {
    // TODO: Replace with actual API call using usePosts composable
    await new Promise(resolve => setTimeout(resolve, 1500)) // Simulated API call
    
    successMessage.value = isAdmin.value 
      ? 'Post published successfully!' 
      : 'Post submitted for approval!'
    
    // Redirect after success
    setTimeout(() => {
      router.push('/my-posts')
    }, 2000)
  } catch (error: any) {
    errorMessage.value = 'Failed to create post. Please try again.'
    errors.value = error.errors || {}
  } finally {
    loading.value = false
  }
}

// Handle save as draft
const saveDraft = async () => {
  errorMessage.value = ''
  errors.value = {}
  successMessage.value = ''
  loading.value = true

  try {
    // TODO: Replace with actual API call using usePosts composable
    await new Promise(resolve => setTimeout(resolve, 1500)) // Simulated API call
    
    successMessage.value = 'Post saved as draft!'
    
    // Redirect after success
    setTimeout(() => {
      router.push('/my-posts')
    }, 2000)
  } catch (error: any) {
    errorMessage.value = 'Failed to save draft. Please try again.'
    errors.value = error.errors || {}
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
