<template>
  <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6 lg:px-8 py-12">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div class="text-center">
        <h2 class="text-3xl font-bold text-gray-900">Create your account</h2>
        <p class="mt-2 text-gray-600">Join our community of writers and readers</p>
      </div>

      <!-- Registration Form -->
      <form class="mt-8 space-y-6 bg-white p-8 rounded-lg shadow-lg">
        <div class="space-y-4">
          <!-- Name Field -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              Full name
            </label>
            <input
              id="name"
              type="text"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="John Doe"
            />
          </div>

          <!-- Email Field -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email address
            </label>
            <input
              id="email"
              type="email"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="you@example.com"
            />
          </div>

          <!-- Password Field -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Password
            </label>
            <input
              id="password"
              type="password"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="••••••••"
            />
          </div>

          <!-- Confirm Password Field -->
          <div>
            <label for="password-confirm" class="block text-sm font-medium text-gray-700 mb-1">
              Confirm password
            </label>
            <input
              id="password-confirm"
              type="password"
              required
              class="appearance-none relative block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
              placeholder="••••••••"
            />
          </div>

          <!-- Role Selection -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              I want to join as
            </label>
            <div class="grid grid-cols-2 gap-3">
              <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none">
                <input type="radio" name="role" value="reader" class="sr-only" checked />
                <div class="flex w-full items-center justify-between">
                  <div class="flex items-center">
                    <div class="text-sm">
                      <p class="font-medium text-gray-900">👤 Reader</p>
                      <p class="text-gray-500 text-xs">Browse & comment</p>
                    </div>
                  </div>
                </div>
              </label>
              <label class="relative flex cursor-pointer rounded-lg border border-gray-300 bg-white p-4 hover:border-indigo-500 focus:outline-none">
                <input type="radio" name="role" value="author" class="sr-only" />
                <div class="flex w-full items-center justify-between">
                  <div class="flex items-center">
                    <div class="text-sm">
                      <p class="font-medium text-gray-900">✍️ Author</p>
                      <p class="text-gray-500 text-xs">Write posts</p>
                    </div>
                  </div>
                </div>
              </label>
            </div>
          </div>
        </div>

        <!-- Terms & Conditions -->
        <div class="flex items-start">
          <input
            id="terms"
            type="checkbox"
            required
            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer mt-0.5"
          />
          <label for="terms" class="ml-2 block text-sm text-gray-700 cursor-pointer">
            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms and Conditions</a> and <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
          </label>
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
          >
            Create account
          </button>
        </div>

        <!-- Divider -->
        <div class="relative">
          <div class="absolute inset-0 flex items-center">
            <div class="w-full border-t border-gray-300"></div>
          </div>
          <div class="relative flex justify-center text-sm">
            <span class="px-2 bg-white text-gray-500">Or</span>
          </div>
        </div>

        <!-- Login Link -->
        <div class="text-center">
          <p class="text-sm text-gray-600">
            Already have an account?
            <NuxtLink to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">
              Sign in
            </NuxtLink>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
useHead({
  title: 'Sign Up - Epic Blog',
  meta: [
    { name: 'description', content: 'Create your Epic Blog account and join our community' }
  ]
})
</script>
