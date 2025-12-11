<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Loading State -->
    <div v-if="loading" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center py-12">
        <svg class="animate-spin h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600">Loading post...</p>
      </div>
    </div>

    <!-- Error State -->
    <div v-else-if="error" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading post</h3>
        <p class="mt-1 text-sm text-red-600">{{ error }}</p>
        <div class="mt-6">
          <NuxtLink 
            to="/"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
          >
            Back to Home
          </NuxtLink>
        </div>
      </div>
    </div>

    <!-- Post Content -->
    <article v-else-if="post" class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <!-- Post Header -->
      <header class="mb-8">
        <!-- Status Badge (for authors and admins) -->
        <div v-if="isAuthenticated && (isPostAuthor || isAdmin)" class="mb-4">
          <span :class="[
            'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium',
            getStatusColor(post.status)
          ]">
            {{ post.status }}
          </span>
        </div>

        <!-- Title -->
        <h1 class="text-4xl font-bold text-gray-900 mb-4">
          {{ post.title }}
        </h1>

        <!-- Meta Information -->
        <div class="flex items-center gap-6 text-gray-600">
          <!-- Author -->
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="h-10 w-10 rounded-full bg-indigo-600 flex items-center justify-center">
                <span class="text-white font-medium">
                  {{ post.user?.name?.charAt(0).toUpperCase() }}
                </span>
              </div>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-900">
                {{ post.user?.name }}
              </p>
              <p class="text-sm text-gray-500">
                {{ formatDate(post.published_at || post.created_at) }}
              </p>
            </div>
          </div>

          <!-- Reading Time (approximate) -->
          <div class="flex items-center text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ readingTime }} min read
          </div>
        </div>

        <!-- Action Buttons (for post author and admin) -->
        <div v-if="isAuthenticated && (isPostAuthor || isAdmin)" class="mt-6 flex items-center gap-3">
          <NuxtLink 
            v-if="isPostAuthor"
            :to="`/posts/${post.id}/edit`"
            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
            Edit Post
          </NuxtLink>

          <button 
            v-if="isAdmin && post.status === 'pending'"
            @click="handleApprove"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Approve
          </button>

          <button 
            v-if="isAdmin && post.status === 'pending'"
            @click="handleReject"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Reject
          </button>

          <button 
            v-if="isPostAuthor || isAdmin"
            @click="handleDelete"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
          >
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Delete
          </button>
        </div>
      </header>

      <!-- Divider -->
      <div class="border-t border-gray-200 my-8"></div>

      <!-- Post Content -->
      <div class="prose prose-lg prose-indigo max-w-none">
        <div v-html="formattedContent"></div>
      </div>

      <!-- Divider -->
      <div class="border-t border-gray-200 my-12"></div>

      <!-- Like Section -->
      <div class="flex items-center justify-center mb-12">
        <LikeButton 
          v-if="post.id"
          :post-id="post.id"
          :initial-likes-count="post.likes_count || 0"
          :initial-is-liked="post.is_liked_by_auth || false"
          @liked="handlePostLiked"
          @unliked="handlePostUnliked"
        />
      </div>

      <!-- Divider -->
      <div class="border-t border-gray-200 my-12"></div>

      <!-- Comments Section -->
      <section class="mb-12">
        <CommentSection 
          v-if="post.id"
          :post-id="post.id"
          @comment-added="handleCommentAdded"
          @comment-updated="handleCommentUpdated"
          @comment-deleted="handleCommentDeleted"
        />
      </section>

      <!-- Back to Posts -->
      <div class="mt-12">
        <NuxtLink 
          to="/"
          class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
          </svg>
          Back to all posts
        </NuxtLink>
      </div>
    </article>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const { user, isAuthenticated } = useAuth()
const { isAdmin } = useRole()
const { getPostBySlug, deletePost, approvePost, rejectPost } = usePosts()

const slug = route.params.slug as string

const post = ref<any>(null)
const loading = ref(true)
const error = ref('')

// Check if current user is the post author
const isPostAuthor = computed(() => {
  return isAuthenticated.value && post.value && user.value && post.value.user?.id === user.value.id
})

// Calculate reading time (approximate)
const readingTime = computed(() => {
  if (!post.value?.content) return 0
  const wordsPerMinute = 200
  const wordCount = post.value.content.split(/\s+/).length
  return Math.ceil(wordCount / wordsPerMinute)
})

// Format content (convert line breaks to paragraphs)
const formattedContent = computed(() => {
  if (!post.value?.content) return ''
  
  // Simple formatting: convert double line breaks to paragraphs
  return post.value.content
    .split('\n\n')
    .map((paragraph: string) => `<p>${paragraph.replace(/\n/g, '<br>')}</p>`)
    .join('')
})

// Fetch post
const fetchPost = async () => {
  loading.value = true
  error.value = ''

  const result = await getPostBySlug(slug)

  if (result.success) {
    post.value = result.data.data
    
    // Update page title
    useHead({
      title: `${post.value.title} - Epic Blog`,
      meta: [
        { name: 'description', content: post.value.excerpt || post.value.title }
      ]
    })
  } else {
    error.value = result.error
  }

  loading.value = false
}

// Format date
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'long', 
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

// Handle delete
const handleDelete = async () => {
  if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
    return
  }

  const result = await deletePost(post.value.id)

  if (result.success) {
    router.push(isPostAuthor.value ? '/my-posts' : '/admin/posts')
  } else {
    alert(result.error)
  }
}

// Handle approve
const handleApprove = async () => {
  if (!confirm('Approve this post? It will be published and visible to all users.')) {
    return
  }

  const result = await approvePost(post.value.id)

  if (result.success) {
    post.value = result.data.data
  } else {
    alert(result.error)
  }
}

// Handle reject
const handleReject = async () => {
  if (!confirm('Reject this post? The author will be able to see the rejection status.')) {
    return
  }

  const result = await rejectPost(post.value.id)

  if (result.success) {
    post.value = result.data.data
  } else {
    alert(result.error)
  }
}

// Handle post liked
const handlePostLiked = () => {
  if (post.value) {
    post.value.likes_count = (post.value.likes_count || 0) + 1
    post.value.is_liked_by_auth = true
  }
}

// Handle post unliked
const handlePostUnliked = () => {
  if (post.value) {
    post.value.likes_count = Math.max(0, (post.value.likes_count || 0) - 1)
    post.value.is_liked_by_auth = false
  }
}

// Handle comment added
const handleCommentAdded = () => {
  if (post.value) {
    post.value.comments_count = (post.value.comments_count || 0) + 1
  }
}

// Handle comment updated
const handleCommentUpdated = () => {
  // No need to update count, just refresh handled by CommentSection
}

// Handle comment deleted
const handleCommentDeleted = () => {
  if (post.value) {
    post.value.comments_count = Math.max(0, (post.value.comments_count || 0) - 1)
  }
}

// Fetch post on mount
onMounted(() => {
  fetchPost()
})
</script>

<style scoped>
/* Prose styling for content */
.prose p {
  margin-bottom: 1.25em;
  line-height: 1.75;
}

.prose p:last-child {
  margin-bottom: 0;
}

.prose strong {
  font-weight: 600;
  color: #111827;
}

.prose em {
  font-style: italic;
}

.prose h1, .prose h2, .prose h3, .prose h4 {
  font-weight: 700;
  margin-top: 2em;
  margin-bottom: 1em;
  line-height: 1.25;
  color: #111827;
}

.prose h1 {
  font-size: 2.25em;
}

.prose h2 {
  font-size: 1.875em;
}

.prose h3 {
  font-size: 1.5em;
}

.prose ul, .prose ol {
  margin-top: 1.25em;
  margin-bottom: 1.25em;
  padding-left: 1.625em;
}

.prose li {
  margin-top: 0.5em;
  margin-bottom: 0.5em;
}

.prose blockquote {
  font-style: italic;
  border-left: 4px solid #e5e7eb;
  padding-left: 1em;
  margin-left: 0;
  margin-right: 0;
  color: #6b7280;
}

.prose code {
  background-color: #f3f4f6;
  padding: 0.2em 0.4em;
  border-radius: 0.25rem;
  font-size: 0.875em;
  font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
}

.prose a {
  color: #4f46e5;
  text-decoration: underline;
}

.prose a:hover {
  color: #4338ca;
}
</style>
