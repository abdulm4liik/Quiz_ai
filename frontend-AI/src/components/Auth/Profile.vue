<template>
    <div class="relative bg-navy-soft rounded-lg shadow">
      <div class="p-5">
        <div class="text-center">
          <p class="my-3 text-2xl font-semibold leading-5 text-white">
            {{ user?.email }}
          </p>
          <div class="border-b border-white py-4" />
        </div>
  
       
        <div class="w-full py-6">
          <input
            v-model="name"
            type="text"
            class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
            placeholder="Enter new name"
            :class="{ 'border-red-400': errors?.name }"
          />
          <span v-if="errors?.name" class="text-red-500 text-sm">{{ errors?.name }}</span>
  
          <div
            @click="updateName"
            class="inline-flex h-10 mt-1 w-full items-center cursor-pointer justify-center gap-2 rounded border-4 border-double border-beige-dark bg-beige-light p-2 text-navy-soft"
          >
            Update Name
          </div>
        </div>
  
        <input
          v-model="currentPassword"
          class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Current Password"
          :class="{ 'border-red-400': errors?.current_password }"
        />
        <span v-if="errors?.current_password" class="text-red-400 text-sm">{{ errors?.current_password[0] }}</span>
  
        <input
          v-model="password"
          class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="New Password"
          :class="{ 'border-red-400': errors?.password }"
        />
        <span v-if="errors?.password" class="text-red-400 text-sm">{{ errors?.password[0] }}</span>
  
        <input
          v-model="passwordConfirmation"
          class="block w-full px-3 py-2 my-4 border-2 rounded-md text-lg font-medium text-navy-soft outline outline-navy-soft focus:border-beige-dark bg-beige-light"
          placeholder="Confirm Password"
          :class="{ 'border-red-400': errors?.password_confirmation }"
        />
        <span v-if="errors?.password_confirmation" class="text-red-400 text-sm">{{ errors?.password_confirmation[0] }}</span>
  
        <div
          @click="updatePassword"
          class="inline-flex h-10 mt-1 w-full items-center cursor-pointer justify-center gap-2 rounded border-4 border-double border-beige-dark bg-beige-light p-2 text-navy-soft"
        >
          Update Password
        </div>
      </div>
    </div>
  </template>
  
  <script setup>
  import { ref, computed, watch } from 'vue';
  import { useProfileStore } from '@/stores/profileStore';
  import { useAuthStore } from '@/stores/auth'

  
  const profileStore = useProfileStore();
  const authStore = useAuthStore();

  const name = ref("");



  const currentPassword = ref('');
  const password = ref('');
  const passwordConfirmation = ref('');
  
  const errors = computed(() => profileStore.errors); 
  const user = computed(() => authStore.user); 
  
  const updateName = () => {
    profileStore.updateName(name.value);
  };
  
  const updatePassword = () => {
  profileStore.updatePassword(
    currentPassword.value, 
    password.value, 
    passwordConfirmation.value
  );
};

watch(user, (newUser) => {
  if (newUser) {
    name.value = newUser.name;
  }
}, { immediate: true });


  </script>
  