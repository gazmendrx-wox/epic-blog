<template>
  <div class="bg-white rounded-lg shadow-sm p-6 space-y-6">
    <!-- Comments Header -->
    <div class="flex items-center justify-between border-b pb-4">
      <h3 class="text-xl font-bold text-gray-900">
        Comments ({{ totalComments }})
      </h3>
    </div>

    <!-- Comment Form (Authenticated Users) -->
    <CommentSectionCommentForm
      v-if="isAuthenticated"
      v-model="newCommentContent"
      :submit-text="submitting ? 'Posting...' : 'Post Comment'"
      :disabled="submitting"
      :error="submitError"
      @submit="handleSubmitComment"
    />

    <!-- Login Prompt for Non-Authenticated Users -->
    <CommentSectionAuthPrompt v-else />

    <!-- Comments List -->
    <div class="space-y-4">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-8">
        <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-2 text-gray-600">Loading comments...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading comments</h3>
        <p class="mt-1 text-sm text-red-600">{{ error }}</p>
        <button 
          @click="fetchComments()"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Try Again
        </button>
      </div>

      <!-- Comments -->
      <div v-else-if="comments.length > 0" class="space-y-4">
        <CommentSectionCommentItem
          v-for="comment in comments"
          :key="comment.id"
          :comment="comment"
          :is-editing="editingCommentId === comment.id"
          :saving="updating"
          @edit="startEdit"
          @delete="handleDeleteComment"
          @cancel-edit="cancelEdit"
          @save-edit="handleUpdateComment(comment.id, $event)"
        />
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-8">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No comments yet</h3>
        <p class="mt-1 text-sm text-gray-500">Be the first to comment!</p>
      </div>

      <!-- Pagination -->
      <Pagination 
        v-if="pagination && comments.length > 0"
        :pagination="pagination"
        @page-change="handlePageChange"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
interface User {
  id: number
  name: string
  email: string
  role: string
}

interface Comment {
  id: number
  post_id: number
  user?: User
  content: string
  created_at: string
  updated_at: string
  can_edit: boolean
  can_delete: boolean
}

const props = defineProps<{
  postId: number
}>()

const emit = defineEmits<{
  commentAdded: []
  commentUpdated: []
  commentDeleted: []
}>()

const { isAuthenticated } = useAuth()
const { getPostComments, createComment, updateComment, deleteComment } = useComments()

// State
const comments = ref<Comment[]>([])
const pagination = ref<any>(null)
const totalComments = ref(0)
const loading = ref(false)
const error = ref('')

// New comment
const newCommentContent = ref('')
const submitting = ref(false)
const submitError = ref('')

// Edit comment
const editingCommentId = ref<number | null>(null)
const updating = ref(false)

// Fetch comments
const fetchComments = async (page: number = 1) => {
  loading.value = true
  error.value = ''

  const result = await getPostComments(props.postId, page, 20)

  if (result.success) {
    comments.value = result.data.data
    pagination.value = result.data.meta
    totalComments.value = result.data.meta?.total || 0
  } else {
    error.value = result.error
  }

  loading.value = false
}

// Submit new comment
const handleSubmitComment = async () => {
  if (!newCommentContent.value.trim()) return

  submitting.value = true
  submitError.value = ''

  const result = await createComment(props.postId, newCommentContent.value)

  if (result.success) {
    newCommentContent.value = ''
    await fetchComments() // Refresh comments list
    emit('commentAdded')
  } else {
    submitError.value = result.error
  }

  submitting.value = false
}

// Start editing
const startEdit = (comment: Comment) => {
  editingCommentId.value = comment.id
}

// Cancel edit
const cancelEdit = () => {
  editingCommentId.value = null
}

// Update comment
const handleUpdateComment = async (commentId: number, content: string) => {
  if (!content.trim()) return

  updating.value = true

  const result = await updateComment(commentId, content)

  if (result.success) {
    await fetchComments()
    cancelEdit()
    emit('commentUpdated')
  }

  updating.value = false
}

// Delete comment
const handleDeleteComment = async (commentId: number) => {
  if (!confirm('Are you sure you want to delete this comment?')) return

  const result = await deleteComment(commentId)

  if (result.success) {
    await fetchComments()
    emit('commentDeleted')
  }
}

// Handle page change
const handlePageChange = (page: number) => {
  fetchComments(page)
}

// Fetch on mount
onMounted(() => {
  fetchComments()
})
</script>
