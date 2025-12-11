<template>
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900">Manage Submitted Posts</h1>
      <p class="mt-2 text-gray-600">Review and manage all submitted blog posts</p>
    </div>

    <!-- Posts Table Component -->
    <PostsTable 
      :posts="posts"
      :pagination="pagination"
      :loading="loading"
      :error="error"
      loading-message="Loading submitted posts..."
      empty-message="No posts submitted yet."
      :show-author="true"
      :show-approve-button="true"
      :show-reject-button="true"
      @delete="handleDelete"
      @approve="handleApprove"
      @reject="handleReject"
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
  title: 'Manage Posts - Epic Blog Admin',
  meta: [
    { name: 'description', content: 'Manage all submitted blog posts' }
  ]
})

const { isAdmin } = useRole()
const { getAdminPosts, deletePost, approvePost, rejectPost } = usePosts()
const router = useRouter()

// Redirect if user is not admin
if (!isAdmin.value) {
  router.push('/')
}

const posts = ref<any[]>([])
const pagination = ref<any>(null)
const currentPage = ref(1)
const loading = ref(true)
const error = ref('')

// Fetch all posts for admin
const fetchPosts = async (page: number = 1) => {
  loading.value = true
  error.value = ''
  currentPage.value = page
  
  const result = await getAdminPosts(page, 50)
  
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
  if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
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

// Approve a post
const handleApprove = async (postId: number) => {
  if (!confirm('Approve this post? It will be published and visible to all users.')) {
    return
  }
  
  const result = await approvePost(postId)
  
  if (result.success) {
    // Update post status in local array
    const post = posts.value.find(p => p.id === postId)
    if (post && result.data.data) {
      Object.assign(post, result.data.data)
    }
  } else {
    alert(result.error)
  }
}

// Reject a post
const handleReject = async (postId: number) => {
  if (!confirm('Reject this post? The author will be able to see the rejection status.')) {
    return
  }
  
  const result = await rejectPost(postId)
  
  if (result.success) {
    // Update post status in local array
    const post = posts.value.find(p => p.id === postId)
    if (post && result.data.data) {
      Object.assign(post, result.data.data)
    }
  } else {
    alert(result.error)
  }
}

// Fetch posts on mount
onMounted(() => {
  fetchPosts()
})
</script>
