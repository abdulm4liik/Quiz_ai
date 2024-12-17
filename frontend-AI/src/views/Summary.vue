<script setup>
import Layout from '../layouts/Layout.vue';
import FileUpload from '../components/Auth/Upload.vue';
import { ref } from 'vue';

const responseType = 0; 
const summary = ref(''); 
const show = ref(false); 


const handleFileUploadResponse = (data) => {

  if (data?.data?.response_data?.summary?.summary) {
    summary.value = data.data.response_data.summary.summary;
    show.value = true;  
  }
};



const close = () => {
  show.value = false;
  
};
</script>

<template>
  <Layout>
    <Modal :show="show" @close="close">
      <div class="p-6">
        <h3 class="text-xl font-semibold text-navy pb-2">Summary</h3>
        <div class="flex">{{ summary }}</div>
        <div class="flex justify-center">
        <button
        @click="close"
        class="mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-400 transition w-full"
      >
        Close
      </button>
    </div>
      </div>
    </Modal>

    <template #header>
      <div class="font-semibold text-2xl text-navy leading-tight bg-white w-full sm:w-1/2 md:w-1/4 p-4 sm:p-2 text-center rounded-md">
        AI Summary Dashboard
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-light-gray overflow-hidden shadow-sm sm:rounded-lg border">
          <div class="p-10 text-navy-soft">
            <div class="mb-6">
              <!-- Upload component -->
              <FileUpload 
                :responseType="responseType"
                @file-upload-success="handleFileUploadResponse"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<style scoped>
.bg-light-gray {
  background-color: #f7f7f7;
}
</style>
