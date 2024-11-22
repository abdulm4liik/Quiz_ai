<template>
    <div class="w-full">
      <label for="fileUpload"
        class="bg-white text-navy font-semibold text-xl rounded-lg w-full h-64 flex flex-col items-center justify-center cursor-pointer border-2 border-beige-dark border-dashed mx-auto font-sans">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-16 mb-4 fill-navy" viewBox="0 0 32 32">
          <path
            d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
            data-original="#000000" />
          <path
            d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
            data-original="#000000" />
        </svg>
        Upload Files
  
        <!-- File Input with conditional file type acceptance -->
        <input 
          type="file" 
          id="fileUpload" 
          class="hidden" 
          :accept="fileLimit === 'pdf' ? '.pdf' : 'image/*'" 
          @change="handleFileChange" 
          :multiple="fileLimit === 'image'"
        />
        <p class="text-sm font-medium text-navy mt-3">
          {{ fileLimit === 'pdf' ? "Select one PDF" : "Select 1 to 5 image files (JPG, PNG, GIF)" }}
        </p>
      </label>
  
      <!-- Error Message -->
      <p v-if="errorMessage" class="text-sm text-red-500 mt-2">{{ errorMessage }}</p>
  
      <!-- File Type Selection -->
      <div class="mt-4 text-sm font-medium">
        <!-- PDF/PowerPoint button -->
        <button 
          @click="fileLimit = 'pdf'" 
          :class="{'bg-navy text-beige-light': fileLimit === 'pdf', 'bg-white border-beige-dark border text-navy-muted': fileLimit !== 'pdf'}" 
          class="px-4 py-2 rounded w-full sm:w-auto mb-2 sm:mb-0"
        >
          PDF
        </button>
      
        <!-- Images button -->
        <button 
          @click="fileLimit = 'image'" 
          :class="{'bg-navy text-beige-light': fileLimit === 'image', 'bg-white border-beige-dark border text-navy-muted': fileLimit !== 'image'}" 
          class="px-4 py-2 rounded w-full sm:w-auto mb-2 sm:mb-0 sm:ml-2"
        >
          Images (JPG, PNG, GIF)
        </button>
      </div>
      
      <!-- Notice at the Bottom -->
      <p class="text-sm text-navy-sky mt-4">
        * You can upload 1 PDF, or 1-5 image files (JPG, PNG, GIF).
      </p>
    </div>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  
  const fileLimit = ref('pdf'); // Options: 'pdf', 'image'
  const errorMessage = ref("");
  
  const handleFileChange = (event) => {
    const files = event.target.files;
    errorMessage.value = ""; // Reset error message
  
    // Validate based on file type selected (PDF/PowerPoint or Image)
    if (fileLimit.value === 'image') {
      // Image validation (1-5 images)
      if (files.length < 1 || files.length > 5) {
        errorMessage.value = "You can upload 1 to 5 image files only.";
        event.target.value = ""; // Clear input
        return;
      }
  
      const invalidFiles = Array.from(files).filter(file => !file.type.startsWith('image/'));
      if (invalidFiles.length > 0) {
        errorMessage.value = "Only image files (JPG, PNG, GIF) are allowed.";
        event.target.value = ""; // Clear input
      }
    } else {
      // PDF/PowerPoint validation (only 1 file allowed)
      if (files.length !== 1) {
        errorMessage.value = "You can upload only one PDF or PowerPoint file.";
        event.target.value = ""; // Clear input
        return;
      }
  
      const validTypes = [
        'application/pdf',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation'
      ];
      const invalidFiles = Array.from(files).filter(file => !validTypes.includes(file.type));
      if (invalidFiles.length > 0) {
        errorMessage.value = "Invalid file type selected. Please upload a PDF or PowerPoint file.";
        event.target.value = ""; // Clear input
      }
    }
  };
  </script>
  
  <style scoped>
  /* Custom styles for layout or UI elements */
  </style>
  