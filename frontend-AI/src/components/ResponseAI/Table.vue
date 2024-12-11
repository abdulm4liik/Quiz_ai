<template>
 
    <table class="w-full text-left table-auto min-w-max">
      <thead>
        <tr>
          <th class="p-4 border-b border-slate-200 bg-beige-light">
            <p class="text-sm font-normal leading-none text-navy-sky text-left">AI Response Title</p>
          </th>
          <th class="p-4 border-b border-slate-200 bg-beige-light text-left">
            <p class="text-sm font-normal leading-none text-navy-sky">Response Type</p>
          </th>
          <th class="p-4 border-b border-slate-200 bg-beige-light"></th>
          <th class="p-4 border-b border-slate-200 bg-beige-light text-left"></th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="response in aiResponses" 
          :key="response.id" 
          class="hover:bg-slate-50 border-b border-slate-200"
        >
          <td class="p-4 py-5">
            <p class="block font-semibold text-sm text-navy-muted text-left">{{ response.title }}</p>
          </td>
          <td class="text-left py-5 p-4">
            <p class="text-sm text-navy font-semibold">
              {{ response.response_type == 1 ? 'Quiz' : 'Summary' }}
            </p>
          </td>
          <td class="p-4 py-5" @click="$emit('data', response)">
            <p class="text-sm text-emerald-500 cursor-pointer">
              <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 4.45962C9.91153 4.16968 10.9104 4 12 4C16.1819 4 19.028 6.49956 20.7251 8.70433C21.575 9.80853 22 10.3606 22 12C22 13.6394 21.575 14.1915 20.7251 15.2957C19.028 17.5004 16.1819 20 12 20C7.81811 20 4.97196 17.5004 3.27489 15.2957C2.42496 14.1915 2 13.6394 2 12C2 10.3606 2.42496 9.80853 3.27489 8.70433C3.75612 8.07914 4.32973 7.43025 5 6.82137" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M15 12C15 13.6569 13.6569 15 12 15C10.3431 15 9 13.6569 9 12C9 10.3431 10.3431 9 12 9C13.6569 9 15 10.3431 15 12Z" stroke="currentColor" stroke-width="1.5"/>
              </svg>
            </p>
          </td>
          <td class="text-center py-5" @click="$emit('delete', response)">
            <p class="text-sm text-rose-500 cursor-pointer">
              <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.5001 6H3.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M9.5 11L10 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M14.5 11L14 16" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                <path d="M6.5 6C6.55588 6 6.58382 6 6.60915 5.99936C7.43259 5.97849 8.15902 5.45491 8.43922 4.68032C8.44784 4.65649 8.45667 4.62999 8.47434 4.57697L8.57143 4.28571C8.65431 4.03708 8.69575 3.91276 8.75071 3.8072C8.97001 3.38607 9.37574 3.09364 9.84461 3.01877C9.96213 3 10.0932 3 10.3553 3H13.6447C13.9068 3 14.0379 3 14.1554 3.01877C14.6243 3.09364 15.03 3.38607 15.2493 3.8072C15.3043 3.91276 15.3457 4.03708 15.4286 4.28571L15.5257 4.57697C15.5433 4.62992 15.5522 4.65651 15.5608 4.68032C15.841 5.45491 16.5674 5.97849 17.3909 5.99936C17.4162 6 17.4441 6 17.5 6" stroke="currentColor" stroke-width="1.5"/>
                <path d="M18.3735 15.3991C18.1965 18.054 18.108 19.3815 17.243 20.1907C16.378 21 15.0476 21 12.3868 21H11.6134C8.9526 21 7.6222 21 6.75719 20.1907C5.89218 19.3815 5.80368 18.054 5.62669 15.3991L5.16675 8.5M18.8334 8.5L18.6334 11.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
              </svg>
            </p>
          </td>
        </tr>
      </tbody>
    </table>
  
</template>

<script setup>
defineProps({
  aiResponses: {
    type: Array,
    required: true,
  },
});
defineEmits(['delete','data']);

</script>
