<script setup>
import { ref, computed, onMounted } from 'vue';
import { useAIResponsesStore } from '@/stores/AIresponse';
import AIResponseTable from '@/components/ResponseAI/Table.vue';
import Pagination from '@/components/ResponseAI/Pagination.vue';
import AuthenticatedLayout from '@/layouts/AuthenticatedLayout.vue';

const aiResponsesStore = useAIResponsesStore();

const isModalVisible = ref(false);
const responseToDelete = ref(null);


const searchQuery = ref('');


const confirmDelete = (response) => {
  responseToDelete.value = response;
  isModalVisible.value = true;
};


const closeModal = () => {
  isModalVisible.value = false;
  responseToDelete.value = null;
};


const deleteAIResponse = () => {
  if (responseToDelete.value) {
    aiResponsesStore.deleteAIResponse(responseToDelete.value.id).then(() => {
      fetchPage(aiResponsesStore.pagination.current_page); // Refresh current page
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
</script>


<template>
  <AuthenticatedLayout>
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
              <!-- Delete Confirmation Modal -->
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
            </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles for layout or UI elements */
.bg-light-gray {
  background-color: #f7f7f7;
}
</style>
