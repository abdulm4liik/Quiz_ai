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
    loading: false, 
    errorMessage: '', 
    response: null,  
  }),

  actions: {
    async fetchAIResponses(page = 1, search = '') {
      try {
        const response = await axios.get('/api/ai-responses', {
          params: { page, search },
        });
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

    searchAIResponses(searchTerm) {
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      this.searchTimeout = setTimeout(() => {
        this.pagination.current_page = 1;
        this.fetchAIResponses(1, searchTerm);
      }, 300);
    },

    async deleteAIResponse(id) {
      try {
        await axios.delete(`/api/ai-responses/${id}`);
        this.aiResponses = this.aiResponses.filter((response) => response.id != id);
      } catch (error) {
        console.error('Error deleting AI response:', error);
      }
    },

    async uploadPDF(formData) {
      this.loading = true;  
      this.errorMessage = ''; 
      this.response = null;   

      try {
        const response = await axios.post('/api/ai-responses/', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        this.response = response.data;
        this.loading = false;  
        return response.data;
      } catch (error) {
        this.errorMessage = error.response ? error.response.data : error.message;
        this.loading = false; 
      }
    },
    async storeAnswers(payload) {
      try {
        const response = await axios.put('/api/ai-responses/', payload); 
        return response.data; 
      } catch (error) {
        console.error("Error while storing answers:", error);
        throw error;
      }
    },
  },
});
