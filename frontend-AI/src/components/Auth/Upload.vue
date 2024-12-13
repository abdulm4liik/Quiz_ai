<template>
  <div class="w-full" v-if="user?.name">
    <div v-if="aiResponsesStore.loading" class="absolute inset-0 flex justify-center items-center  text-navy-soft bg-opacity-50 z-50  bg-white">
    <div   class="animate-spin">
      <svg xmlns="http://www.w3.org/2000/svg" width="200" height="200" viewBox="0 0 24 24"><path fill="currentColor" d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity="0.25"/><circle cx="12" cy="2.5" r="1.5" fill="currentColor"></circle></svg>
    </div>
  </div>
    <label for="fileUpload"
      class="bg-white text-navy font-semibold text-xl rounded-lg w-full h-64 flex flex-col items-center justify-center cursor-pointer border-2 border-beige-dark border-dashed mx-auto font-sans">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-16 mb-4 fill-navy" viewBox="0 0 32 32">
        <path
          d="M23.75 11.044a7.99 7.99 0 0 0-15.5-.009A8 8 0 0 0 9 27h3a1 1 0 0 0 0-2H9a6 6 0 0 1-.035-12 1.038 1.038 0 0 0 1.1-.854 5.991 5.991 0 0 1 11.862 0A1.08 1.08 0 0 0 23 13a6 6 0 0 1 0 12h-3a1 1 0 0 0 0 2h3a8 8 0 0 0 .75-15.956z"
          data-original="#000000" />
        <path
          d="M20.293 19.707a1 1 0 0 0 1.414-1.414l-5-5a1 1 0 0 0-1.414 0l-5 5a1 1 0 0 0 1.414 1.414L15 16.414V29a1 1 0 0 0 2 0V16.414z"
          data-original="#000000" />
      </svg>
      Upload PDF

      <input 
        type="file" 
        id="fileUpload" 
        class="hidden" 
        accept=".pdf" 
        @change="handleFileChange"
      />
      <p class="text-sm font-medium text-navy mt-3">
        Select one PDF file.
      </p>
    </label>

    <p v-if="errorMessage" class="text-sm text-red-500 mt-2">{{ errorMessage }}</p>

    <div v-if="pageCount > 0" class="mt-4">
      <label for="pageSelection" class="text-sm font-medium text-navy">
        Select pages (up to 5 pages). E.g., 2-7 or 5 for a single page: <span>({{ fileName }})</span>
      </label>
      <input
        v-model="pageRange"
        type="text"
        id="pageSelection"
        class="mt-2 px-3 py-2 border-2 rounded-md w-full"
        placeholder="Enter page range (e.g., 2-7)"
      />
      <p v-if="pageError" class="text-sm text-red-500 mt-2">{{ pageError }}</p>
      <p class="text-sm text-navy mt-2">Total Pages: {{ pageCount }}</p>
      <button 
        @click="sendSelectedPages"
        class="mt-4 bg-navy text-white p-2 rounded-lg"
      >
        Send Selected Pages
      </button>
    </div>

    <p class="text-sm text-navy-sky mt-4">
      * You can upload only one PDF file. 
    </p>
  </div>

  <div v-else>
    <p class="lg:text-3xl  text-navy-sky mt-4">
      * You must log in first.
  </p>
</div>
  
</template>

<script setup>
import { ref,defineProps } from 'vue';
import { useAIResponsesStore } from '../../stores/AIresponse';  
import { PDFDocument } from 'pdf-lib';
import { useAuthStore } from '../../stores/auth'

const { user } = useAuthStore()

const emit = defineEmits();

const props = defineProps({
  responseType: {
    type: Number,
    required: true,
  },
});

const errorMessage = ref("");
const pageRange = ref("");
const pageCount = ref(0);
const pageError = ref("");
const selectedPages = ref([]);
const fileName = ref("");
const aiResponsesStore = useAIResponsesStore();

const handleFileChange = async (event) => {
  const file = event.target.files[0];
  errorMessage.value = "";
  pageError.value = "";

  if (!file || file.type != 'application/pdf') {
    errorMessage.value = "Invalid file type selected. Please upload a PDF file.";
    event.target.value = "";
    return;
  }

  try {
    const arrayBuffer = await file.arrayBuffer();
    const pdfDoc = await PDFDocument.load(arrayBuffer);
    pageCount.value = pdfDoc.getPages().length;
    fileName.value = file.name;
  } catch (err) {
    errorMessage.value = "Failed to load PDF file.";
    event.target.value = "";
  }
};

const validatePageRange = () => {
  const pages = pageRange.value.split('-').map(Number);
  selectedPages.value = [];

  if (pages.length == 1) {
    const page = pages[0];
    if (isNaN(page) || page < 1 || page > pageCount.value) {
      pageError.value = `Please enter a valid page number between 1 and ${pageCount.value}.`;
      return false;
    }
    selectedPages.value.push(page);
    return true;
  }

  if (pages.length != 2 || isNaN(pages[0]) || isNaN(pages[1])) {
    pageError.value = "Please enter a valid page range (e.g., 2-7).";
    return false;
  }

  const [start, end] = pages;

  if (start < 1 || end > pageCount.value || start > end) {
    pageError.value = `Please select a valid range between 1 and ${pageCount.value}.`;
    return false;
  }

  if (end - start + 1 > 5) {
    pageError.value = "You can select up to 5 pages only.";
    return false;
  }

  for (let i = start; i <= end; i++) {
    selectedPages.value.push(i);
  }
  return true;
};

const sendSelectedPages = async () => {
  if (!validatePageRange()) {
    return;
  }

  const file = document.getElementById('fileUpload').files[0];
  const arrayBuffer = await file.arrayBuffer();
  const pdfDoc = await PDFDocument.load(arrayBuffer);
  const newPdfDoc = await PDFDocument.create();


  for (const pageIndex of selectedPages.value) {
    const [page] = await newPdfDoc.copyPages(pdfDoc, [pageIndex - 1]);
    newPdfDoc.addPage(page);
  }

  const pdfBytes = await newPdfDoc.save();

  const formData = new FormData();
  const blob = new Blob([pdfBytes], { type: 'application/pdf' });
  formData.append('pdf', blob, file.name);
  formData.append('response_type', props.responseType);

  try {
    const response = await aiResponsesStore.uploadPDF(formData); 
  
    emit('file-upload-success', response);


    document.getElementById('fileUpload').value = ''; 
    pageCount.value = ''; 
    pageRange.value = ''; 
    fileName.value = ''; 
  } catch (error) {
    console.error('Error sending PDF:', error);
  }
};

</script>
