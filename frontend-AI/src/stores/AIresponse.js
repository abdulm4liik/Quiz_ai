import { defineStore } from 'pinia';
import axios from '@/utils/axios';

export const useAIResponsesStore = defineStore('aiResponses', {
  state: () => ({
    aiResponses: [],
    pagination: {
      current_page: 1,
      total: 0,
      last_page: 1,
      from: 1,
      to: 6,
    },
    searchTimeout: null,
  }),

  actions: {
    // Fetch AI responses with search and pagination support
    async fetchAIResponses(page = 1, search = '') {
      try {
        const response = await axios.get('/api/ai-responses', {
          params: {
            page,
            search,
          },
        });

        // Set the AI responses and update pagination
        this.aiResponses = response.data.data;
        this.pagination = response.data.meta || {
          current_page: 1,
          total: 0,
          last_page: 1,
          from: 1,
          to: 6,
        };
      } catch (error) {
        console.error('Error fetching AI responses:', error);
      }
    },

    // Search AI responses with debounce to avoid excessive API calls
    searchAIResponses(searchTerm) {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }

      this.searchTimeout = setTimeout(() => {
        // Reset pagination when a new search starts
        this.pagination.current_page = 1;
        this.fetchAIResponses(1, searchTerm);
      }, 300);
    },

    // Delete an AI response and update the local list
    async deleteAIResponse(id) {
      try {
        await axios.delete(`/api/ai-responses/${id}`);
        this.aiResponses = this.aiResponses.filter((response) => response.id !== id);
      } catch (error) {
        console.error('Error deleting AI response:', error);
      }
    }
  },
});
