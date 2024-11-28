<template>
    <div class="flex justify-between items-center px-4 py-3">
      <div />
      <div class="flex space-x-1">
        <button 
          class="px-3 py-1 min-w-9 min-h-9 text-sm font-bold text-navy-soft border-2 border-navy-soft bg-white rounded"
          :disabled="currentPage == 1"
          @click="$emit('fetch', currentPage - 1)"
        >
          Prev
        </button>
        <button 
          v-for="page in lastPage" 
          :key="page" 
          :class="{'bg-navy-soft text-white': page == currentPage, 'text-beige-dark': page !== currentPage}"
          class="px-3 py-1 min-w-9 min-h-9 text-sm font-bold text-navy-soft border-2 border-navy-soft rounded"
          @click="$emit('fetch', page)"
        >
          {{ page }}
        </button>
        <button 
          class="px-3 py-1 min-w-9 min-h-9 text-sm font-bold text-navy-soft border-2 border-navy-soft bg-white rounded"
          :disabled="currentPage == lastPage"
          @click="$emit('fetch', currentPage + 1)"
        >
          Next
        </button>
      </div>
    </div>
  </template>
  
  <script setup>
  defineProps({
    currentPage: {
      type: Number,
      required: true,
    },
    lastPage: {
      type: Number,
      required: true,
    },
  });
  defineEmits(['fetch']);
  </script>
  