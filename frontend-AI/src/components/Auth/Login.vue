<script setup>
import { ref, watchEffect } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import Checkbox from '@/components/Checkbox.vue'

// Form state for login and register
const form = ref({
  email: '',
  password: '',
  remember: false,
})
const formRegister = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

// Shared state
const processing = ref(false)
const errors = ref({})
const status = ref(null)
const showLogin = ref(true)  // This will toggle between login and register form
const route = useRoute()

watchEffect(() => {
  if (route.query.reset && route.query.reset?.length > 0) {
    status.value = atob(route.query.reset)
  } else {
    status.value = null
  }
})

const { login, register } = useAuthStore()

// Handle login
const handleLogin = async () => {
  try {
    await login(processing, errors, form.value)
  } catch (e) {
    console.error(e)
  }
}

// Handle register
const handleRegister = async () => {
  try {
    await register(processing, errors, formRegister.value)
  } catch (e) {
    console.error(e)
  }
}
</script>

<template>
  <!-- Login Form -->
  <div v-if="showLogin" class="relative bg-navy-soft rounded-lg shadow">
    <div class="p-5">
      <div class="text-center">
        <p class="mb-3 text-2xl font-semibold leading-5 text-white">
          Login to your account
        </p>
        <div class="border-b border-white py-4"/>
      </div>

      <div class="w-full mt-8">
        <!-- Email Input -->
        <input
          v-model="form.email"
          name="email"
          type="email"
          autocomplete="email"
           class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Email Address"
          :class="{'border-red-400': errors.email}"
        />

        <!-- Password Input -->
        <input
          v-model="form.password"
          name="password"
          type="password"
          autocomplete="current-password"
           class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Password"
          :class="{'border-red-400': errors.password}"
        />

        <div class="mt-8">
          <label class="flex items-center">
            <Checkbox name="remember" v-model:checked="form.remember" />
            <span class="ml-2 text-lg text-white">Remember me</span>
          </label>
        </div>

        <!-- Login Button -->
        <div
          @click="handleLogin"
          class="inline-flex h-10 mt-6 w-full items-center cursor-pointer justify-center gap-2 rounded border-4 border-double border-beige-dark bg-beige-light p-2 text-navy-soft "
        >
          Login
        </div>
      </div>

      <div class="mt-6 text-center text-sm text-beige-light">
        Don't have an account? 
        <span @click="showLogin = false" class="font-medium text-beige-dark cursor-pointer">
          Sign up
        </span>
      </div>
    </div>
  </div>

  <!-- Register Form -->
  <div v-else class="relative bg-navy-soft rounded-lg shadow">
    <div class="p-5">
      <div class="text-center">
        <p class="mb-3 text-2xl font-semibold leading-5 text-white">
          Create an account
        </p>
        <div class="border-b border-white py-4"/>
      </div>

      <div class="w-full mt-8">
        <!-- Name Input -->
        <input
          v-model="formRegister.name"
          name="name"
          type="text"
           class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Full Name"
          :class="{'border-red-400': errors.name}"
        />

        <!-- Email Input -->
        <input
          v-model="formRegister.email"
          name="email"
          type="email"
          autocomplete="email"
           class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Email Address"
          :class="{'border-red-400': errors.email}"
        />

        <!-- Password Input -->
        <input
          v-model="formRegister.password"
          name="password"
          type="password"
          autocomplete="new-password"
           class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Password"
          :class="{'border-red-400': errors.password}"
        />

        <!-- Password Confirmation Input -->
        <input
          v-model="formRegister.password_confirmation"
          name="password_confirmation"
          type="password"
          autocomplete="new-password"
           class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Confirm Password"
          :class="{'border-red-400': errors.password_confirmation}"
        />

   

        <!-- Register Button -->
        <div
          @click="handleRegister"
          class="inline-flex h-10 mt-6 w-full items-center cursor-pointer justify-center gap-2 rounded border-4 border-double border-beige-dark bg-beige-light p-2 text-navy-soft "
        >
          Register
        </div>
      </div>

      <div class="mt-6 text-center text-sm text-beige-light">
        Already have an account? 
        <span @click="showLogin = true" class="font-medium text-beige-dark cursor-pointer">
          Login
        </span>
      </div>
    </div>
  </div>
</template>
