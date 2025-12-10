<template>
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <!-- Header -->
    <div class="text-center mb-12">
      <h1 class="text-4xl font-bold text-gray-900 mb-4">About Epic Blog</h1>
      <p class="text-xl text-gray-600">Learn more about our platform and mission</p>
    </div>

    <!-- Content -->
    <div class="bg-white rounded-lg shadow-lg p-8 space-y-8">
      <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Our Mission</h2>
        <p class="text-gray-700 leading-relaxed">
          Epic Blog is a modern blogging platform designed to empower writers and readers alike. 
          We believe in the power of quality content and community-driven discussions. Our platform 
          provides a seamless experience for creating, sharing, and discovering amazing stories.
        </p>
      </section>

      <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">What We Offer</h2>
        <div class="grid md:grid-cols-2 gap-6">
          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Quality Content</h3>
              <p class="text-gray-600 text-sm">Curated posts reviewed by our editorial team</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Active Community</h3>
              <p class="text-gray-600 text-sm">Engage with readers through comments and likes</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Stay Updated</h3>
              <p class="text-gray-600 text-sm">Newsletter notifications for new posts</p>
            </div>
          </div>

          <div class="flex items-start space-x-3">
            <div class="flex-shrink-0">
              <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900 mb-1">Secure Platform</h3>
              <p class="text-gray-600 text-sm">Your data is protected with modern security</p>
            </div>
          </div>
        </div>
      </section>

      <section>
        <h2 class="text-2xl font-bold text-gray-900 mb-4">User Roles</h2>
        <div class="space-y-4">
          <div class="border-l-4 border-indigo-600 pl-4">
            <h3 class="font-semibold text-gray-900">👤 Readers</h3>
            <p class="text-gray-600 text-sm">Browse posts, like content, and leave comments</p>
          </div>
          <div class="border-l-4 border-purple-600 pl-4">
            <h3 class="font-semibold text-gray-900">✍️ Authors</h3>
            <p class="text-gray-600 text-sm">Create and manage your own blog posts</p>
          </div>
          <div class="border-l-4 border-pink-600 pl-4">
            <h3 class="font-semibold text-gray-900">👑 Admins</h3>
            <p class="text-gray-600 text-sm">Moderate content and manage the platform</p>
          </div>
        </div>
      </section>

      <section class="bg-gray-50 rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Join Our Community</h2>
        <p class="text-gray-600 mb-6">
          Ready to start your blogging journey or discover great content?
        </p>
        <div class="flex justify-center space-x-4">
          <NuxtLink 
            to="/register" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-lg font-semibold transition"
          >
            Sign Up Now
          </NuxtLink>
          <NuxtLink 
            to="/login" 
            class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition"
          >
            Login
          </NuxtLink>
        </div>
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'About - Epic Blog',
  meta: [
    { name: 'description', content: 'Learn more about Epic Blog, our mission, and what we offer to our community' }
  ]
})
</script>
