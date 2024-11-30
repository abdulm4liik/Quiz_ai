<script setup>
import { ref,onMounted } from 'vue'
import { RouterLink, useRoute } from 'vue-router'
import ApplicationLogo from '@/components/ApplicationLogo.vue'
import Dropdown from '@/components/Dropdown.vue'
import DropdownButton from '@/components/DropdownButton.vue'
import NavLink from '@/components/NavLink.vue'
import ResponsiveNavLink from '@/components/ResponsiveNavLink.vue'
import ResponsiveNavButton from '@/components/ResponsiveNavButton.vue'
import { useAuthStore } from '@/stores/auth'
import Login from '@/components/Auth/Login.vue';
import Profile from '@/components/Auth/Profile.vue';

const route = useRoute()

const { user, logout } = useAuthStore()

const showingNavigationDropdown = ref(false)



const isLoginModalOpen = ref(false);

const goToLogin = () => {
  isLoginModalOpen.value = true;
};

const closeLoginModal = () => {
  isLoginModalOpen.value = false;
};



const isProfileModalOpen = ref(false);

const goToProfile = () => {
  isProfileModalOpen.value = true;
};

const closeProfileModal = () => {
  isProfileModalOpen.value = false;
};


const showDemo = ref(false);


const openDemo = () => {
  showDemo.value = true;
};

const closeDemo = () => {
  showDemo.value = false;
  localStorage.setItem('isModalShown', 'true');
};



onMounted(() => {
  const isModalShown = localStorage.getItem('isModalShown');
  if (!isModalShown) {
    openDemo(); 
  }
});

const handleLogin = () => {
  if (!user) {
    goToLogin();  
  }
};
</script>

<template>
  
  <div>
    <Modal   :show="showDemo">
      <div class="p-6">
          <h2 class="text-xl font-semibold text-gray-800">Quiz-AI Beta Version</h2>
          <p class="text-gray-600 mt-2">
            Welcome to Quiz-AI! This application is currently in its beta stage and not yet fully completed. 
          </p>
          <p class="text-gray-600 mt-2">
            It is built to enhance development skills and demonstrates the use of the GPT API for AI-powered quiz generation.
          </p>
          <p class="text-sm text-gray-400 mt-2">
            Please note: Since this is a beta version, some features may be missing or incomplete.
          </p>
        <button
          @click="closeDemo"
          class="mt-4 px-4 py-2 bg-navy text-white rounded-lg hover:bg-navy-sky transition"
        >
          Close
        </button>
      </div>
    </Modal>


    <Modal :show="isLoginModalOpen" @close="closeLoginModal">
  
        <login/>
    

    </Modal>
    <Modal :show="isProfileModalOpen" @close="closeProfileModal">
  
      <Profile/>
  

  </Modal>
    <div class="min-h-screen bg-beige-dark ">
      <nav class="bg-beige  border-b border-gray-100 ">
        <!-- Primary Navigation Menu -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex">
      

              <!-- Navigation Links -->
              <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                <NavLink @click="handleLogin" :to="{ name: 'Activity' }" :active="route.name == 'Activity'">
                  My-Activity
                </NavLink>
                <NavLink :to="{ name: 'Summary' }" :active="route.name == 'Summary'">
                  AI-Summary
                </NavLink>
                <NavLink :to="{ name: 'Quiz' }" :active="route.name == 'Quiz'">
                  AI-Quiz
                </NavLink>
              </div>
            </div>
            <div class="hidden sm:flex sm:items-center sm:ml-6">
              <template v-if="user?.name">
                <div class="ml-3 relative">
                  <Dropdown align="right" width="48">
                    <template #trigger>
                      <span class="inline-flex rounded-md bg-white p-1">
                        <button
                          type="button"
                          class="inline-flex items-center px-3 py-2 border border-transparent text-xl leading-4 font-medium rounded-md text-navy-soft hover:text-navy-soft focus:outline-none transition ease-in-out duration-150"
                        >
                          {{ user.name }}
            
                          <svg
                            class="ml-2 -mr-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                          >
                            <path
                              fill-rule="evenodd"
                              d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                              clip-rule="evenodd"
                            />
                          </svg>
                        </button>
                      </span>
                    </template>
            
                    <template #content>
                      <DropdownButton @click="logout()">Log Out</DropdownButton>
                      <DropdownButton @click="goToProfile()">Profile</DropdownButton>
                    </template>
                  </Dropdown>
                </div>
              </template>
            
              <template v-else>
                <button
                  @click="goToLogin()"
                  class="px-4 py-2 bg-navy text-white rounded-md text-lg font-medium hover:bg-navy-light focus:outline-none transition ease-in-out duration-150"
                >
                  Log-In
                </button>
              </template>
            </div>
            

            <!-- Hamburger -->
            <div class="-mr-2 flex items-center sm:hidden">
              <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400  hover:text-gray-500  hover:bg-gray-100  focus:outline-none focus:bg-gray-100  focus:text-gray-500  transition duration-150 ease-in-out"
              >
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                  <path
                    :class="{
                      hidden: showingNavigationDropdown,
                      'inline-flex': !showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                  />
                  <path
                    :class="{
                      hidden: !showingNavigationDropdown,
                      'inline-flex': showingNavigationDropdown,
                    }"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                  />
                </svg>
              </button>
            </div>
          </div>
        </div>

        <!-- Responsive Navigation Menu -->
        <div
          :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
          }"
          class="sm:hidden"
        >
          <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink :to="{ name: 'Quiz' }" :active="route.name == 'Quiz'">
              Quiz
            </ResponsiveNavLink>
          </div>

          <!-- Responsive Settings Options -->
          <div class="pt-4 pb-1 border-t border-gray-200 ">
            <div class="px-4">
              <div class="font-medium text-base text-gray-800 ">
                {{ user?.name }}
              </div>
              <div class="font-medium text-sm text-gray-500">
                {{ user?.email }}
              </div>
            </div>

            <div class="mt-3 space-y-1">
              <ResponsiveNavButton @click="logout()"> Log Out </ResponsiveNavButton>
            </div>
          </div>
        </div>
      </nav>

      <!-- Page Heading -->
      <header class="bg-beige-dark " v-if="$slots.header">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
          <slot name="header" />
        </div>
      </header>

      <!-- Page Content -->
      <main>
        <slot />
      </main>
    </div>
  </div>
</template>
