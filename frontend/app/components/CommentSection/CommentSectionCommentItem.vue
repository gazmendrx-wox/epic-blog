<template>
  <div class="border-b border-gray-200 pb-4 last:border-0">
    <!-- Comment Header -->
    <div class="flex items-start justify-between mb-2">
      <div class="flex items-center space-x-3">
        <!-- User Avatar -->
        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center flex-shrink-0">
          <span class="text-indigo-600 font-semibold text-sm">
            {{ getUserInitials(comment.user?.name) }}
          </span>
        </div>
        
        <!-- User Info -->
        <div>
          <p class="text-sm font-medium text-gray-900">{{ comment.user?.name }}</p>
          <p class="text-xs text-gray-500">{{ formatDate(comment.created_at) }}</p>
        </div>
      </div>

      <!-- Actions Menu -->
      <div v-if="comment.can_edit || comment.can_delete" class="flex items-center space-x-2">
        <button
          v-if="comment.can_edit"
          @click="$emit('edit', comment)"
          class="text-sm text-indigo-600 hover:text-indigo-700"
        >
          Edit
        </button>
        <button
          v-if="comment.can_delete"
          @click="$emit('delete', comment.id)"
          class="text-sm text-red-600 hover:text-red-700"
        >
          Delete
        </button>
      </div>
    </div>

    <!-- Comment Content or Edit Form -->
    <div v-if="isEditing" class="ml-13 space-y-2">
      <textarea
        v-model="editContent"
        rows="3"
        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
      ></textarea>
      <div class="flex justify-end space-x-2">
        <button
          @click="$emit('cancel-edit')"
          class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200"
        >
          Cancel
        </button>
        <button
          @click="$emit('save-edit', editContent)"
          :disabled="!editContent.trim() || saving"
          class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50"
        >
          {{ saving ? 'Saving...' : 'Save' }}
        </button>
      </div>
    </div>
    <div v-else class="ml-13">
      <p class="text-gray-700 whitespace-pre-wrap">{{ comment.content }}</p>
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
  comment: Comment
  isEditing?: boolean
  saving?: boolean
}>()

defineEmits<{
  edit: [comment: Comment]
  delete: [commentId: number]
  'cancel-edit': []
  'save-edit': [content: string]
}>()

const editContent = ref(props.comment.content)

// Watch for editing state changes to reset content
watch(() => props.isEditing, (isEditing) => {
  if (isEditing) {
    editContent.value = props.comment.content
  }
})

// Helper functions
const getUserInitials = (name?: string) => {
  if (!name) return 'U'
  return name
    .split(' ')
    .map(word => word[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  const now = new Date()
  const diff = now.getTime() - date.getTime()
  const minutes = Math.floor(diff / 60000)
  const hours = Math.floor(minutes / 60)
  const days = Math.floor(hours / 24)

  if (minutes < 1) return 'Just now'
  if (minutes < 60) return `${minutes} minute${minutes > 1 ? 's' : ''} ago`
  if (hours < 24) return `${hours} hour${hours > 1 ? 's' : ''} ago`
  if (days < 7) return `${days} day${days > 1 ? 's' : ''} ago`
  
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}
</script>
