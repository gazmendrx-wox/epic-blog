<template>
  <div>
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-indigo-600 to-purple-700 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
        <div class="text-center">
          <h1 class="text-4xl md:text-5xl font-bold mb-4">
            Welcome to Epic Blog! 👋
          </h1>
          <p class="text-xl md:text-2xl text-indigo-100 mb-8">
            Discover amazing stories, tutorials, and insights from our community
          </p>
          <div class="flex justify-center space-x-4">
            <NuxtLink 
              to="/register" 
              class="bg-white text-indigo-600 hover:bg-gray-100 px-8 py-3 rounded-lg font-semibold transition shadow-lg"
            >
              Get Started
            </NuxtLink>
            <NuxtLink 
              to="#posts" 
              class="bg-indigo-700 hover:bg-indigo-800 px-8 py-3 rounded-lg font-semibold transition border-2 border-white"
            >
              Explore Posts
            </NuxtLink>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="bg-white py-12 shadow-sm">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
          <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">{{ mockPosts.length }}+</div>
            <div class="text-gray-600">Published Posts</div>
          </div>
          <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">50+</div>
            <div class="text-gray-600">Active Authors</div>
          </div>
          <div>
            <div class="text-4xl font-bold text-indigo-600 mb-2">1000+</div>
            <div class="text-gray-600">Community Members</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Posts Section -->
    <section id="posts" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="mb-12">
        <h2 class="text-3xl font-bold text-gray-900 mb-4">Latest Posts</h2>
        <p class="text-gray-600">Check out our most recent articles and tutorials</p>
      </div>

      <!-- Posts Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <PostCard 
          v-for="post in mockPosts" 
          :key="post.id" 
          :post="post" 
        />
      </div>

      <!-- Load More Button -->
      <div class="text-center mt-12">
        <button 
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold transition inline-flex items-center space-x-2"
        >
          <span>Load More Posts</span>
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
          </svg>
        </button>
      </div>
    </section>

    <!-- CTA Section -->
    <section class="bg-gray-900 text-white py-16">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl font-bold mb-4">Ready to Share Your Story?</h2>
        <p class="text-gray-300 mb-8 text-lg">Join our community of writers and start publishing today</p>
        <NuxtLink 
          to="/register" 
          class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-lg font-semibold transition inline-block"
        >
          Create Your Account
        </NuxtLink>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
// Mock post data
const mockPosts = [
  {
    id: 1,
    title: "Getting Started with Vue 3 and Nuxt.js",
    slug: "getting-started-vue-nuxt",
    excerpt: "Learn the fundamentals of building modern web applications with Vue 3 and Nuxt.js. This comprehensive guide covers everything from setup to deployment.",
    author: "John Doe",
    published_at: "2025-12-08",
    likes: 42,
    comments: 12
  },
  {
    id: 2,
    title: "Building RESTful APIs with Laravel",
    slug: "building-restful-apis-laravel",
    excerpt: "A deep dive into creating robust and scalable REST APIs using Laravel. We'll cover routing, controllers, middleware, and authentication.",
    author: "Jane Smith",
    published_at: "2025-12-07",
    likes: 38,
    comments: 8
  },
  {
    id: 3,
    title: "Docker Best Practices for Development",
    slug: "docker-best-practices",
    excerpt: "Optimize your development workflow with Docker. Learn about multi-stage builds, volume management, and container orchestration.",
    author: "Mike Johnson",
    published_at: "2025-12-06",
    likes: 56,
    comments: 15
  },
  {
    id: 4,
    title: "TypeScript Tips and Tricks",
    slug: "typescript-tips-tricks",
    excerpt: "Master TypeScript with these advanced techniques. From utility types to decorators, elevate your type-safe coding skills.",
    author: "Sarah Williams",
    published_at: "2025-12-05",
    likes: 67,
    comments: 23
  },
  {
    id: 5,
    title: "Understanding Database Relationships",
    slug: "database-relationships",
    excerpt: "Explore the different types of database relationships and how to implement them effectively in your Laravel applications using Eloquent ORM.",
    author: "David Brown",
    published_at: "2025-12-04",
    likes: 45,
    comments: 10
  },
  {
    id: 6,
    title: "Tailwind CSS: A Complete Guide",
    slug: "tailwind-css-guide",
    excerpt: "Everything you need to know about Tailwind CSS. From basic utility classes to creating custom components and themes.",
    author: "Emily Davis",
    published_at: "2025-12-03",
    likes: 73,
    comments: 18
  }
]

// Set page meta
useHead({
  title: 'Home - Epic Blog',
  meta: [
    { name: 'description', content: 'Welcome to Epic Blog - A modern blogging platform built with Laravel and Nuxt.js' }
  ]
})
</script>
