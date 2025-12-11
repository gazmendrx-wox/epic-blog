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

    <!-- Posts Table Component -->
    <PostsTable 
      :posts="posts"
      :pagination="pagination"
      :loading="loading"
      :error="error"
      loading-message="Loading your posts..."
      empty-message="Get started by creating a new post."
      :show-edit-button="true"
      :show-create-button="true"
      @delete="handleDelete"
      @retry="fetchPosts"
      @page-change="handlePageChange"
    />
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
const { getMyPosts, deletePost } = usePosts()
const router = useRouter()

// Redirect if user cannot create posts
if (!canCreatePosts.value) {
  router.push('/')
}

const posts = ref<any[]>([])
const pagination = ref<any>(null)
const currentPage = ref(1)
const loading = ref(true)
const error = ref('')

// Fetch user's posts
const fetchPosts = async (page: number = 1) => {
  loading.value = true
  error.value = ''
  currentPage.value = page
  
  const result = await getMyPosts(page, 50)
  
  if (result.success) {
    posts.value = result.data.data || []
    pagination.value = result.data.meta || null
  } else {
    error.value = result.error
  }
  
  loading.value = false
}

// Handle page change
const handlePageChange = (page: number) => {
  fetchPosts(page)
}

// Delete a post
const handleDelete = async (postId: number) => {
  if (!confirm('Are you sure you want to delete this post?')) {
    return
  }
  
  const result = await deletePost(postId)
  
  if (result.success) {
    // Remove post from local array
    posts.value = posts.value.filter(post => post.id !== postId)
  } else {
    alert(result.error)
  }
}

// Fetch posts on mount
onMounted(() => {
  fetchPosts()
})
</script>
