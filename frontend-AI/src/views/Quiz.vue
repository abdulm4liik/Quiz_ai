<script setup>
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';
import FileUpload from '@/components/Auth/Upload.vue';
import { ref, computed, nextTick } from 'vue';
import { useAIResponsesStore } from '@/stores/AIresponse';

const storeAnswers = useAIResponsesStore();

const responseType = 1;
const quiz = ref([]); // To store quiz data
const show = ref(false); // Whether the quiz modal is visible
const answers = ref([]);  // Array to store answers
const score = ref(0);  // To store the score
const quizCompleted = ref(false);  // Track if quiz is completed
const quizId = ref(null);  // To store the quiz ID

// Handle the response from the file upload
const handleFileUploadResponse = (data) => {
  if (data?.data?.response_data?.quiz) {
    quiz.value = data.data.response_data.quiz;
    quizId.value = data.data.response_data.quiz.id;  // Extract the quiz ID
    answers.value = new Array(quiz.value.length).fill(null);  // Initialize answers array
    show.value = true;  // Show quiz modal when file is uploaded
    console.log("Quiz loaded, showing modal:", show.value);
    console.log("Quiz ID:", quizId.value);  // Debugging the quiz ID
  }
};

const close = () => {
  show.value = false; 
  quizCompleted.value = false; 
  answers.value = new Array(quiz.value.length).fill(null);  
  score.value = 0;  
  quiz.value = [];  
  quizId.value = null;  // Reset quiz ID
};

// Submit the quiz and calculate score
const submitQuiz = async () => {
  // Calculate score
  score.value = quiz.value.reduce((acc, question, index) => {
    if (answers.value[index] === question.correct_answer) {
      return acc + 1;
    }
    return acc;
  }, 0);

  console.log("Score calculated:", score.value);  // Debugging the score calculation

  quizCompleted.value = true;  // Mark quiz as completed
  await nextTick();  // Ensure Vue updates the view after score calculation
  console.log("Quiz completed:", quizCompleted.value);  


  if (quizId.value !== null) {
    try {
      const response = await storeAnswers.storeAnswers(quiz.value, answers.value, quizId.value);  // Use quizId here
      console.log('Quiz results stored:', response);
    } catch (error) {
      console.error('Error storing quiz results:', error);
    }
  } else {
    console.error('Quiz ID is not available');
  }
};


const isQuizComplete = computed(() => {
  return answers.value.every(answer => answer !== null);
});
</script>

<template>
  <AuthenticatedLayout>
    <!-- Modal Section -->
    <Modal :show="show">
      <div class="p-6">
        <h3 class="text-xl font-semibold text-navy pb-2">Quiz</h3>
        <div v-if="!quizCompleted">
        <div v-if="quiz.length">
          <div v-for="(question, index) in quiz" :key="index" class="mb-4">
            <h4 class="text-lg font-semibold text-navy">{{ question.question }}</h4>
            <div class="space-y-2">
              <div v-for="(option, i) in question.options" :key="i" class="flex items-center">
                <input 
                  type="radio" 
                  :name="'question-' + index" 
                  :value="i" 
                  v-model="answers[index]"
                  class="mr-2" 
                />
                <label>{{ option }}</label>
              </div>
            </div>
          </div>
        </div>

        <button
          @click="submitQuiz"
          :disabled="!isQuizComplete"
          class="mt-4 px-4 py-2 bg-navy text-white rounded-lg hover:bg-navy-sky transition disabled:opacity-50"
        >
          Finish Quiz
        </button>
      </div>
        <div v-else class="">
          <div class="my-4 p-2 text-center border border-navy rounded">
          <p class="font-semibold text-lg text-green-700">Your Score: {{ score }} / {{ quiz.length }}</p>
        </div>
        <div class="flex justify-center">
          <button
            @click="close"
            class="mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-400 transition w-full"
          >
            Close
          </button>
        </div>
        </div>
      </div>
    </Modal>

    <template #header>
      <div class="font-semibold text-2xl text-navy leading-tight bg-white w-full sm:w-1/2 md:w-1/4 p-4 sm:p-2 text-center rounded-md">
        AI Quiz Dashboard
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-light-gray overflow-hidden shadow-sm sm:rounded-lg border">
          <div class="p-10 text-navy-soft">
            <div class="mb-6">
              <FileUpload 
                :responseType="responseType"
                @file-upload-success="handleFileUploadResponse"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
.bg-light-gray {
  background-color: #f7f7f7;
}
</style>
