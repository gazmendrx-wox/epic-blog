<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navigation -->
    <NavigationBar />

    <!-- Main Content -->
    <main class="flex-grow">
      <slot />
    </main>

    <!-- Footer -->
    <FooterSection />
  </div>
</template>

<script setup lang="ts">
const { initAuth } = useAuth()

// Initialize auth state from localStorage on mount
onMounted(() => {
  initAuth()
})
</script>
