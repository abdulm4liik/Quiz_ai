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
        this.aiResponses = this.aiResponses.filter((response) => response.id !== id);
      } catch (error) {
        console.error('Error deleting AI response:', error);
      }
    },

    async uploadPDF(formData) {
      this.loading = true;  // Set loading to true when the upload starts
      this.errorMessage = ''; // Reset any previous error message
      this.response = null;    // Reset response data

      try {
        const response = await axios.post('/api/ai-responses/', formData, {
          headers: {
            'Content-Type': 'multipart/form-data',
          },
        });
        console.log('Upload Successful:', JSON.stringify(response.data, null, 2));
        this.response = response.data;
        this.loading = false;  // Set loading to false after upload completes
        return response.data;
      } catch (error) {
        this.errorMessage = error.response ? error.response.data : error.message;
        this.loading = false;  // Set loading to false if an error occurs
      }
    },
    async storeAnswers(quiz, answers, quizId) {
      this.errorMessage = ''; // Reset any previous error message

      // Prepare the data to send
      const data = {
        quiz_id: quizId,
        answers: answers,
        total_score: answers.filter((answer, index) => answer == quiz[index].correct_answer).length,
        correct_answers: quiz.map(question => question.correct_answer), // Sending the correct answers as well
      };

      try {
        // Send the data to the backend
        const response = await axios.put('/api/ai-responses/', data);
        console.log('Quiz answers stored:', response.data);
        this.loading = false;  // Set loading to false after storing the answers
        return response.data;
      } catch (error) {
        this.errorMessage = error.response ? error.response.data : error.message;
        this.loading = false;  // Set loading to false if an error occurs
        console.error('Error storing quiz answers:', this.errorMessage);
      }
    },
  },
});
