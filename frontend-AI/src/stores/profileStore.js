import { defineStore } from 'pinia';
import axios from '@/utils/axios'; 
import { useAuthStore } from '@/stores/auth'

export const useProfileStore = defineStore('profile', {
  state: () => ({
    errors: null, // Store errors (can be used to display error messages in the UI)
  }),

  actions: {
    // Update the user's name
    async updateName(name) {
      const authStore = useAuthStore(); // Access the auth store
      try {
        const response = await axios.put('/api/profile/name', { name });
        authStore.user = response.data.user; // Update the user in the auth store
        this.errors = null; // Clear any previous errors
        location.reload();
      } catch (error) {
        // Log and store errors
        this.errors = error.response?.data || error.message;
        console.error('Failed to update name:', this.errors);
      }
    },

    // Update the user's password
    async updatePassword(currentPassword, newPassword, confirmPassword) {
      if (newPassword !== confirmPassword) {
        this.errors = { password_confirmation: 'Passwords do not match.' };
        return;
      }

      try {
        const response = await axios.put('/api/profile/password', {
          current_password: currentPassword,
          password: newPassword,
          password_confirmation: confirmPassword,
        });
        console.log('Password updated successfully:', response.data);
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
