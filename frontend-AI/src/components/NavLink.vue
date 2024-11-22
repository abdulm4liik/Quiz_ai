<script setup>
import { computed } from 'vue'
import { RouterLink } from 'vue-router'

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
    ? 'inline-flex bg-white items-center px-10 mt-4 rounded-t-md border-b-2 border-navy   text-xl  leading-5 text-navy-muted  focus:outline-none focus:border-navy transition duration-150 ease-in-out'
    : 'inline-flex bg-white items-center px-10 mt-4 rounded-t-md border-b-2 border-transparent text-xl  leading-5 text-navy-muted  hover:text-navy-muted  hover:border-navy-muted  focus:outline-none focus:text-navy-muted  focus:border-navy-muted  transition duration-150 ease-in-out',
)
</script>

<template>
  <RouterLink :to="to" :class="classes">
    <slot />
  </RouterLink>
</template>
