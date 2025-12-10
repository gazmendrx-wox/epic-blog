<template>
  <article class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <!-- Post Image -->
    <div class="relative h-48 bg-gradient-to-r from-indigo-500 to-purple-600">
      <div class="absolute inset-0 flex items-center justify-center">
        <svg class="w-16 h-16 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
        </svg>
      </div>
    </div>

    <!-- Post Content -->
    <div class="p-6">
      <!-- Author and Date -->
      <div class="flex items-center mb-4">
        <div class="w-10 h-10 bg-indigo-100 rounded-full flex items-center justify-center">
          <span class="text-indigo-600 font-semibold text-sm">{{ authorInitials }}</span>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-gray-900">{{ post.author }}</p>
          <p class="text-xs text-gray-500">{{ formattedDate }}</p>
        </div>
      </div>

      <!-- Title -->
      <h3 class="text-xl font-bold text-gray-900 mb-2 hover:text-indigo-600 cursor-pointer transition">
        {{ post.title }}
      </h3>

      <!-- Excerpt -->
      <p class="text-gray-600 mb-4 line-clamp-3">{{ post.excerpt }}</p>

      <!-- Footer with stats and read more -->
      <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        <div class="flex items-center space-x-4 text-sm text-gray-500">
          <!-- Likes -->
          <div class="flex items-center space-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <span>{{ post.likes }}</span>
          </div>
          
          <!-- Comments -->
          <div class="flex items-center space-x-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <span>{{ post.comments }}</span>
          </div>
        </div>

        <!-- Read More Button -->
        <NuxtLink 
          :to="`/posts/${post.slug}`"
          class="text-indigo-600 hover:text-indigo-700 font-medium text-sm flex items-center space-x-1 transition"
        >
          <span>Read more</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </NuxtLink>
      </div>
    </div>
  </article>
</template>

<script setup lang="ts">
interface Post {
  id: number
  title: string
  slug: string
  excerpt: string
  author: string
  published_at: string
  likes: number
  comments: number
}

const props = defineProps<{
  post: Post
}>()

const authorInitials = computed(() => {
  return props.post.author
    .split(' ')
    .map(name => name[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const formattedDate = computed(() => {
  const date = new Date(props.post.published_at)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
})
</script>
