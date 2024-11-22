
<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue'
import { useAuthStore } from '@/stores/auth'

const { user, logout } = useAuthStore()
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex gap-10">
        <p>{{ user?.name }}</p>

        <button @click="logout()">Sign out</button>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped></style>