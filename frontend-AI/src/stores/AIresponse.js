// stores/aiResponsesStore.js
import { defineStore } from 'pinia';
import axios from '@/utils/axios';

export const useAIResponsesStore = defineStore('aiResponses', {
  state: () => ({
    aiResponses: [],
  }),
  actions: {
    async fetchAIResponses() {
      try {
        const response = await axios.get('/api/ai-responses');
        this.aiResponses = response.data.data;
      } catch (error) {
        console.error('Error fetching AI responses:', error);
      }
    },
  },
});
