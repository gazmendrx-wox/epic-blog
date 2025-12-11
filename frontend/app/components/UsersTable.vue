<template>
  <div>
    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
      <nav class="-mb-px flex space-x-8">
        <button
          v-for="tab in tabs"
          :key="tab.value"
          @click="activeTab = tab.value"
          :class="[
            'py-4 px-1 border-b-2 font-medium text-sm transition',
            activeTab === tab.value
              ? 'border-indigo-500 text-indigo-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          {{ tab.label }}
          <span 
            :class="[
              'ml-2 py-0.5 px-2 rounded-full text-xs',
              activeTab === tab.value ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600'
            ]"
          >
            {{ getUserCount(tab.value) }}
          </span>
        </button>
      </nav>
    </div>

    <!-- Users List -->
    <div class="space-y-4">
      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <svg class="animate-spin h-12 w-12 mx-auto text-indigo-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="mt-4 text-gray-600">{{ loadingMessage }}</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading users</h3>
        <p class="mt-1 text-sm text-red-600">{{ error }}</p>
        <button 
          @click="$emit('retry')"
          class="mt-4 inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700"
        >
          Try Again
        </button>
      </div>

      <!-- Empty State -->
      <div v-else-if="filteredUsers.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No users</h3>
        <p class="mt-1 text-sm text-gray-500">{{ emptyMessage }}</p>
      </div>

      <!-- User Cards -->
      <div v-else class="bg-white shadow-sm rounded-lg divide-y divide-gray-200">
        <div 
          v-for="user in filteredUsers" 
          :key="user.id"
          class="p-6 hover:bg-gray-50 transition"
        >
          <div class="flex items-start justify-between">
            <div class="flex items-center flex-1 min-w-0">
              <!-- User Avatar -->
              <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-full bg-indigo-600 flex items-center justify-center">
                  <span class="text-white font-medium text-lg">
                    {{ user.name?.charAt(0).toUpperCase() }}
                  </span>
                </div>
              </div>

              <!-- User Details -->
              <div class="ml-4 flex-1 min-w-0">
                <h3 class="text-lg font-semibold text-gray-900 mb-1">
                  {{ user.name }}
                </h3>
                <p class="text-sm text-gray-600 mb-2">
                  {{ user.email }}
                </p>
                <div class="flex items-center gap-4 text-sm text-gray-500">
                  <span :class="[
                    'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                    getRoleColor(user.role)
                  ]">
                    {{ user.role }}
                  </span>
                  <span v-if="user.is_suspended" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                    Suspended
                  </span>
                  <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ formatDate(user.created_at) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="ml-4 flex-shrink-0 flex items-center gap-2">
              <!-- Unsuspend Button -->
              <button 
                v-if="user.is_suspended"
                @click="$emit('unsuspend', user.id)"
                class="text-green-600 hover:text-green-700 p-2 rounded-md hover:bg-green-50 transition"
                title="Unsuspend user"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </button>

              <!-- Suspend Button -->
              <button 
                v-else
                @click="$emit('suspend', user.id)"
                class="text-orange-600 hover:text-orange-700 p-2 rounded-md hover:bg-orange-50 transition"
                title="Suspend user"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                </svg>
              </button>

              <!-- Delete Button -->
              <button 
                @click="$emit('delete', user.id)"
                class="text-red-600 hover:text-red-700 p-2 rounded-md hover:bg-red-50 transition"
                title="Delete user"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Pagination -->
    <Pagination 
      v-if="pagination && !loading && !error"
      :pagination="pagination"
      @page-change="$emit('page-change', $event)"
    />
  </div>
</template>

<script setup lang="ts">
interface User {
  id: number
  name: string
  email: string
  role: string
  is_suspended: boolean
  suspended_at: string | null
  created_at: string
  updated_at: string
}

interface Pagination {
  current_page: number
  last_page: number
  from: number
  to: number
  total: number
  per_page: number
}

interface Props {
  users: User[]
  pagination?: Pagination | null
  loading?: boolean
  error?: string
  loadingMessage?: string
  emptyMessage?: string
}

const props = withDefaults(defineProps<Props>(), {
  pagination: null,
  loading: false,
  error: '',
  loadingMessage: 'Loading users...',
  emptyMessage: 'No users found.',
})

defineEmits<{
  suspend: [userId: number]
  unsuspend: [userId: number]
  delete: [userId: number]
  retry: []
  'page-change': [page: number]
}>()

// Tabs
const tabs = [
  { label: 'All', value: 'all' },
  { label: 'Admins', value: 'admin' },
  { label: 'Authors', value: 'author' },
  { label: 'Readers', value: 'reader' },
  { label: 'Suspended', value: 'suspended' },
]

const activeTab = ref('all')

// Computed filtered users
const filteredUsers = computed(() => {
  if (activeTab.value === 'all') {
    return props.users
  }
  if (activeTab.value === 'suspended') {
    return props.users.filter(user => user.is_suspended)
  }
  return props.users.filter(user => user.role === activeTab.value && !user.is_suspended)
})

// Get user count for each tab
const getUserCount = (status: string) => {
  if (status === 'all') {
    return props.users.length
  }
  if (status === 'suspended') {
    return props.users.filter(user => user.is_suspended).length
  }
  return props.users.filter(user => user.role === status && !user.is_suspended).length
}

// Format date
const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', { 
    year: 'numeric', 
    month: 'short', 
    day: 'numeric' 
  })
}

// Get role color
const getRoleColor = (role: string) => {
  const colors: Record<string, string> = {
    admin: 'bg-purple-100 text-purple-800',
    author: 'bg-blue-100 text-blue-800',
    reader: 'bg-gray-100 text-gray-800',
  }
  return colors[role] || 'bg-gray-100 text-gray-800'
}
</script>
