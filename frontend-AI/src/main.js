import './assets/main.css'
import './assets/Font.css'
import Modal from './components/Modal.vue';

import { createApp } from 'https://cdn.jsdelivr.net/npm/vue@3.2.47/dist/vue.esm-browser.js';
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

const app = createApp(App)

app.use(createPinia())
app.use(router)
app.component('Modal', Modal);
app.mount('#app')
