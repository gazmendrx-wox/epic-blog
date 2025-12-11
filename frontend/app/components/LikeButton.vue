<template>
  <button
    @click="handleLike"
    :disabled="loading || !isAuthenticated"
    class="flex items-center space-x-1.5 transition-all duration-200 disabled:cursor-not-allowed group"
    :class="[
      isLiked 
        ? 'text-red-500 hover:text-red-600' 
        : 'text-gray-500 hover:text-red-500',
      loading ? 'opacity-70' : '',
      isAuthenticated ? 'cursor-pointer' : 'cursor-not-allowed'
    ]"
    :title="!isAuthenticated ? 'Login to like this post' : isLiked ? 'Unlike' : 'Like'"
  >
    <!-- Heart Icon with Loading Spinner -->
    <div class="relative">
      <!-- Loading Spinner -->
      <svg 
        v-if="loading"
        class="w-5 h-5 animate-spin text-red-500"
        fill="none"
        viewBox="0 0 24 24"
      >
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      
      <!-- Heart Icon -->
      <svg 
        v-else
        class="w-5 h-5 transition-all duration-200"
        :class="{ 
          'scale-110 animate-pulse': isLiked,
          'group-hover:scale-110': !loading 
        }"
        :fill="isLiked ? 'currentColor' : 'none'" 
        stroke="currentColor" 
        stroke-width="2"
        viewBox="0 0 24 24"
      >
        <path 
          stroke-linecap="round" 
          stroke-linejoin="round" 
          d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" 
        />
      </svg>
    </div>
    
    <!-- Like Count -->
    <span 
      class="text-sm font-medium transition-all duration-200"
      :class="{ 'font-bold': isLiked }"
    >
      {{ likesCount }}
    </span>
  </button>
</template>

<script setup lang="ts">
const props = defineProps<{
  postId: number
  initialLikesCount: number
  initialIsLiked: boolean
}>()

const emit = defineEmits<{
  liked: []
  unliked: []
}>()

const { isAuthenticated } = useAuth()
const { toggleLike } = useLikes()

const loading = ref(false)
const likesCount = ref(props.initialLikesCount)
const isLiked = ref(props.initialIsLiked)

// Watch for prop changes (in case parent updates)
watch(() => props.initialLikesCount, (newCount) => {
  likesCount.value = newCount
})

watch(() => props.initialIsLiked, (newIsLiked) => {
  isLiked.value = newIsLiked
})

const handleLike = async () => {
  if (!isAuthenticated.value) {
    // Redirect to login
    navigateTo('/login')
    return
  }

  if (loading.value) return

  loading.value = true

  const result = await toggleLike(props.postId)

  console.log('Toggle like result:', result)

  if (result.success) {
    // Backend wraps data in 'data' object
    const responseData = result.data.data
    
    console.log('Response data:', responseData)
    console.log('New liked state:', responseData.liked)
    console.log('New likes count:', responseData.likes_count)
    
    // Update local state
    isLiked.value = responseData.liked
    likesCount.value = responseData.likes_count

    // Emit event to parent
    if (responseData.liked) {
      emit('liked')
    } else {
      emit('unliked')
    }
  } else {
    console.error('Toggle like failed:', result.error)
  }

  loading.value = false
}
</script>
