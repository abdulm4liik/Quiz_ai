<script setup>
import { computed } from 'vue'

const emit = defineEmits(['update:checked'])

const props = defineProps({
  checked: {
    type: [Array, Boolean],
    required: true,
  },
  value: {
    default: null,
  },
})

const proxyChecked = computed({
  get() {
    return props.checked
  },

  set(val) {
    emit('update:checked', val)
  },
})
</script>

<template>
  <label class="inline-flex items-center">
    <input
      type="checkbox"
      :value="value"
      v-model="proxyChecked"
      class="peer sr-only"
    />
    <span
      class="w-6 h-6 flex items-center justify-center rounded border-2 border-beige-dark bg-beige-light peer-checked:bg-beige-dark peer-checked:border-beige-dark transition"
    >
      <svg
        v-if="proxyChecked"
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        stroke="currentColor"
        viewBox="0 0 24 24"
        stroke-width="2"
        class="w-4 h-4 text-white"
      >
        <path
          stroke-linecap="round"
          stroke-linejoin="round"
          d="M5 13l4 4L19 7"
        />
      </svg>
    </span>
  </label>
</template>
