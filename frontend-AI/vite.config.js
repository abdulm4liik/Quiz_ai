import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@": "/src", 
    },
  },
  server: {
    port: 3000, // This is fine for local development
  },
  build: {
    outDir: "dist", // Ensure this folder is used to deploy static files
    rollupOptions: {
      input: "/src/main.js", // Entry point for your Vue app
    },
  },
  base: "/", // Update this to match the deployment URL if hosted in a subdirectory
});
