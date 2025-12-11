<template>
  <div class="space-y-3">
    <textarea
      v-model="content"
      rows="3"
      :placeholder="placeholder"
      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none"
      :disabled="disabled"
      :maxlength="maxLength"
      @input="handleInput"
    ></textarea>
    
    <div class="flex justify-between items-center">
      <p class="text-sm text-gray-500">
        {{ content.length }}/{{ maxLength }} characters
      </p>
      <button
        @click="$emit('submit')"
        :disabled="!content.trim() || disabled"
        class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed transition"
      >
        {{ submitText }}
      </button>
    </div>

    <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
const props = withDefaults(defineProps<{
  modelValue: string
  placeholder?: string
  submitText?: string
  disabled?: boolean
  error?: string
  maxLength?: number
}>(), {
  placeholder: 'Write a comment...',
  submitText: 'Post Comment',
  disabled: false,
  error: '',
  maxLength: 5000
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
  submit: []
}>()

const content = computed({
  get: () => props.modelValue,
  set: (value) => emit('update:modelValue', value)
})

const handleInput = () => {
  // Optional: Add any input validation logic here
}
</script>
