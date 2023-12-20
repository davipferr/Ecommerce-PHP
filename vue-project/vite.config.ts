import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
      '@api': fileURLToPath(new URL('./api', import.meta.url)),
      '@c': fileURLToPath(new URL('./src/components', import.meta.url)),
      '@v': fileURLToPath(new URL('./src/views', import.meta.url)),
      '@s': fileURLToPath(new URL('./store', import.meta.url)),
      '@u': fileURLToPath(new URL('./utils', import.meta.url)),
      '@t': fileURLToPath(new URL('./types', import.meta.url)),
    }
  }
})
