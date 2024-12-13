import { defineStore } from 'pinia';
import axios from '../utils/axios'; 
import { useAuthStore } from '../stores/auth'

export const useProfileStore = defineStore('profile', {
  state: () => ({
    errors: null,
  }),

  actions: {
    async updateName(name) {
      const authStore = useAuthStore(); 
      try {
        const response = await axios.put('/api/profile/name', { name });
        authStore.user = response.data.user; 
        this.errors = null;
        location.reload();
      } catch (error) {
        this.errors = error.response?.data || error.message;
        console.error('Failed to update name:', this.errors);
      }
    },
    async updatePassword(currentPassword, newPassword, confirmPassword) {
      if (newPassword != confirmPassword) {
        this.errors = { password_confirmation: 'Passwords do not match.' };
        return;
      }

      try {
        const response = await axios.put('/api/profile/password', {
          current_password: currentPassword,
          password: newPassword,
          password_confirmation: confirmPassword,
        });
        this.errors = null;
        location.reload();
      } catch (error) {
        if (error.response?.data?.errors) {
          this.errors = error.response.data.errors;
        } else {
          this.errors = error.message;
        }
        console.error('Failed to update password:', this.errors);
      }
    },
  },
});
