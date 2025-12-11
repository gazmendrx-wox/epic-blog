<template>
  <div class="bg-gray-50 rounded-lg p-4 text-center">
    <p class="text-gray-600 mb-3">{{ message }}</p>
    <NuxtLink
      to="/login"
      class="inline-block px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition"
    >
      {{ buttonText }}
    </NuxtLink>
  </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
  message?: string
  buttonText?: string
}>(), {
  message: 'Sign in to join the conversation',
  buttonText: 'Sign In'
})
</script>
