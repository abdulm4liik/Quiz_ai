import { ref, computed } from 'vue'
import { defineStore } from 'pinia'
import axios from '@/utils/axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref(null)
  const errors = ref({})
  const processing = ref(false)

  const isLoggedIn = computed(() => !!user.value)

  const fetchUser = async () => {
    try {
      const { data } = await axios.get('/api/user')
      user.value = data
    } catch (error) {
      console.error('Error fetching user data:', error)
    }
  }

  const updateProfile = async (formData) => {
    processing.value = true
    errors.value = {}

    try {
      const response = await axios.put('/api/profile', formData)
      user.value = response.data.user // Update the user data
      return response.data.message // Success message
    } catch (error) {
      if (error.response && error.response.data.errors) {
        errors.value = error.response.data.errors
      }
      throw error // Throw error to handle it in the component
    } finally {
      processing.value = false
    }
  }

  return {
    user,
    errors,
    processing,
    isLoggedIn,
    fetchUser,
    updateProfile,
  }
})
