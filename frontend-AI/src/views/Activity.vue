<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import FileUpload from '@/components/Auth/Upload.vue';
</script>

<template>
  <AuthenticatedLayout>
    <template #header>
      <div class="font-semibold text-2xl text-navy leading-tight bg-white w-full sm:w-1/2 md:w-1/4 p-4 sm:p-2 text-center rounded-md">
        My Activity Dashboard
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-light-gray overflow-hidden shadow-sm sm:rounded-lg border">
          <div class="p-10 text-navy-soft">
            <div class="mb-6">
              <FileUpload />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles for layout or UI elements */
.bg-light-gray {
  background-color: #f7f7f7;
}
</style>
