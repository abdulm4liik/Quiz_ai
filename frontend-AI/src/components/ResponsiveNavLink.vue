<script setup>
import { computed } from 'vue'

const props = defineProps({
  to: {
    type: [String, Object],
    required: true,
  },
  active: {
    type: Boolean,
  },
})

const classes = computed(() =>
  props.active
    ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-navy-soft  text-left text-base font-medium text-navy  bg-blue-50  focus:outline-none focus:text-blue-800 focus:bg-blue-100  focus:border-blue-700  transition duration-150 ease-in-out'
    : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600  hover:text-gray-800  hover:bg-gray-50  hover:border-gray-300  focus:outline-none focus:text-gray-800  focus:bg-gray-50  focus:border-gray-300  transition duration-150 ease-in-out',
)
</script>

<template>
  <RouterLink :to="to" :class="classes">
    <slot />
  </RouterLink>
</template>
