import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      'vue': 'vue/dist/vue.esm-bundler.js', // Add this alias
    },
  },
  base: '/subdirectory/', // Keep this if your app is deployed in a subdirectory
  server: {
    port: 3000,
  },
});
