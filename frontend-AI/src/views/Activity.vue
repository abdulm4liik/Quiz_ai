<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAIResponsesStore } from '../stores/AIresponse';
import AIResponseTable from '../components/ResponseAI/Table.vue';
import Pagination from '../components/ResponseAI/Pagination.vue';
import Layout from '../layouts/Layout.vue';

const aiResponsesStore = useAIResponsesStore();

const isModalVisible = ref(false);
const responseData = ref(null);
const isData = ref(false);

const searchQuery = ref('');


const confirmData = (response) =>{
  responseData.value = response;
  isData.value = true;
};
const closeData = () => {
  isData.value = false;
  responseData.value = null;
};

const confirmDelete = (response) => {
  responseData.value = response;
  isModalVisible.value = true;
};


const closeModal = () => {
  isModalVisible.value = false;
  responseData.value = null;
};


const deleteAIResponse = () => {
  if (responseData.value) {
    aiResponsesStore.deleteAIResponse(responseData.value.id).then(() => {
      fetchPage(aiResponsesStore.pagination.current_page); 
    });
    closeModal();
  }
};

const fetchPage = (page = 1) => {
  aiResponsesStore.fetchAIResponses(page, searchQuery.value);
};

const onSearch = () => {
  fetchPage(1);
};


onMounted(() => {
  fetchPage();
});

const pagination = computed(() => aiResponsesStore.pagination);


const isCorrectAnswer = (questionIndex, optionIndex) => {
  return responseData.value.marks.correct_answers && 
         responseData.value.marks.correct_answers[questionIndex] != undefined &&
         responseData.value.marks.correct_answers[questionIndex] == optionIndex;
};

const isWrongAnswer = (questionIndex, optionIndex) => {
  return responseData.value.marks.your_answers &&
         responseData.value.marks.your_answers[questionIndex] != undefined &&
         responseData.value.marks.your_answers[questionIndex] == optionIndex &&
         !isCorrectAnswer(questionIndex, optionIndex);
};

const userSelectedAnswer = (questionIndex) => {
  return responseData.value.marks.your_answers && 
         responseData.value.marks.your_answers[questionIndex] != undefined 
         ? responseData.value.marks.your_answers[questionIndex] 
         : null;
};
</script>


<template>
  <Layout>
    <Modal :show="isData" @close="closeData">
      <div class="p-4">
      <div v-if="responseData.response_type == 1">
        <div v-for="(question, index) in responseData.response_data.quiz" :key="index" class="mb-4">
          <p><strong>Question {{ question.question_number }}:</strong> {{ question.question }}</p>
          <ul>
            <li
              v-for="(option, optionIndex) in question.options"
              :key="optionIndex"
              :class="{
                'text-green-500': isCorrectAnswer(index, optionIndex),
                'text-red-500': isWrongAnswer(index, optionIndex) && userSelectedAnswer(index) == optionIndex
              }"
            >
              {{ option }}
            </li>
          </ul>
        </div>
        <p class="text-lg font-semibold">
          Total Score: {{ responseData?.marks?.total ?? 0 }} / 10
        </p>
      </div>
      <div v-if="responseData?.response_type == 0">
       
        <h3 class="text-xl font-semibold text-navy pb-2">Summary</h3>
        <p>{{ responseData?.response_data?.summary?.summary ?? 'No summary available' }}</p>
      
      </div>
      <div class="flex justify-center">
        <button
          @click="closeData"
          class="mt-4 px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-400 transition w-full"
        >
          Close
        </button>
      </div>
    </div>
    </Modal>
    <template #header>
      <div class="font-semibold text-2xl text-navy leading-tight bg-white w-full sm:w-1/2 md:w-1/4 p-4 sm:p-2 text-center rounded-md">
        My Activity Dashboard
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-light-gray overflow-hidden shadow-sm sm:rounded-lg border">
          <div class="p-5 text-navy-soft">
            <div class="mb-4 md:w-1/3 py-4">
              <input
                v-model="searchQuery"
                class="w-full h-10 px-3 py-2 text-sm border border-slate-300 rounded focus:outline-none focus:border-navy hover:border-navy"
                placeholder="Search for AI responses..."
                @input="onSearch"
              />
            </div>
            <div class="relative flex flex-col w-full h-full overflow-auto text-gray-700 bg-white shadow-md rounded-lg bg-clip-border">
              <AIResponseTable 
                :aiResponses="aiResponsesStore.aiResponses" 
                @data="confirmData"
                @delete="confirmDelete"
              />
              <Pagination 
                :currentPage="pagination.current_page" 
                :lastPage="pagination.last_page" 
                @fetch="fetchPage"
              />
            </div>
          </div>
        </div>
      </div>
    </div>
             
              <Modal :show="isModalVisible" @close="closeModal">
                <div class="p-4">
                <h3 class="text-xl font-semibold mb-4 text-navy-soft">Are you sure?</h3>
                <p class="mb-6 text-navy-soft">Do you really want to delete this response?</p>
                <div class="flex justify-end space-x-4">
                  <button 
                    @click="closeModal" 
                    class="px-4 py-2 bg-gray-400 text-white rounded-md"
                  >
                    Cancel
                  </button>
                  <button 
                    @click="deleteAIResponse" 
                    class="px-4 py-2 bg-red-600 text-white rounded-md"
                  >
                    Delete
                  </button>
                </div>
              </div>
              </Modal>
            </Layout>
</template>

<style scoped>
/* Custom styles for layout or UI elements */
.bg-light-gray {
  background-color: #f7f7f7;
}
</style>
