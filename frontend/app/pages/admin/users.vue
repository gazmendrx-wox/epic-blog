<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
        <p class="mt-2 text-sm text-gray-600">
          Manage all users, roles, and permissions
        </p>
      </div>

      <!-- Platform Stats -->
      <div v-if="stats" class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-indigo-100 rounded-md p-3">
              <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Total Users</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.total_users }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
              <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Admins</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.admins }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Authors</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.authors }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-gray-100 rounded-md p-3">
              <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Readers</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.readers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm p-6">
          <div class="flex items-center">
            <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
              <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-500">Suspended</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.suspended_users }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-lg shadow-sm p-6">
        <UsersTable 
          :users="users"
          :pagination="pagination"
          :loading="loading"
          :error="error"
          @suspend="handleSuspend"
          @unsuspend="handleUnsuspend"
          @delete="handleDelete"
          @retry="fetchUsers"
          @page-change="handlePageChange"
        />
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: 'auth',
})

const { isAdmin } = useRole()
const { getAllUsers, suspendUser, unsuspendUser, deleteUser, getPlatformStats } = useUsers()

// Redirect if not admin
if (!isAdmin.value) {
  navigateTo('/')
}

const users = ref<any[]>([])
const pagination = ref<any>(null)
const currentPage = ref(1)
const loading = ref(false)
const error = ref('')
const stats = ref<any>(null)

// Fetch users
const fetchUsers = async (page: number = 1) => {
  loading.value = true
  error.value = ''

  const result = await getAllUsers(page, 50, true)

  if (result.success) {
    users.value = result.data.data
    pagination.value = result.data.meta
    currentPage.value = page
  } else {
    error.value = result.error
  }

  loading.value = false
}

// Fetch platform stats
const fetchStats = async () => {
  const result = await getPlatformStats()

  if (result.success) {
    stats.value = result.data.data
  }
}

// Handle suspend
const handleSuspend = async (userId: number) => {
  if (!confirm('Are you sure you want to suspend this user? They will be logged out and unable to access the platform.')) {
    return
  }

  const result = await suspendUser(userId)

  if (result.success) {
    // Update local user data
    const userIndex = users.value.findIndex(u => u.id === userId)
    if (userIndex !== -1) {
      users.value[userIndex] = result.data.data
    }
    // Refresh stats
    fetchStats()
  } else {
    alert(result.error)
  }
}

// Handle unsuspend
const handleUnsuspend = async (userId: number) => {
  if (!confirm('Are you sure you want to unsuspend this user? They will regain access to the platform.')) {
    return
  }

  const result = await unsuspendUser(userId)

  if (result.success) {
    // Update local user data
    const userIndex = users.value.findIndex(u => u.id === userId)
    if (userIndex !== -1) {
      users.value[userIndex] = result.data.data
    }
    // Refresh stats
    fetchStats()
  } else {
    alert(result.error)
  }
}

// Handle delete
const handleDelete = async (userId: number) => {
  if (!confirm('Are you sure you want to PERMANENTLY delete this user? This will delete all their posts and cannot be undone.')) {
    return
  }

  const result = await deleteUser(userId)

  if (result.success) {
    // Remove user from local array
    users.value = users.value.filter(u => u.id !== userId)
    // Refresh stats
    fetchStats()
    // Refresh page if needed
    if (users.value.length === 0 && currentPage.value > 1) {
      fetchUsers(currentPage.value - 1)
    }
  } else {
    alert(result.error)
  }
}

// Handle page change
const handlePageChange = (page: number) => {
  fetchUsers(page)
}

// Fetch data on mount
onMounted(() => {
  fetchUsers()
  fetchStats()
})
</script>
