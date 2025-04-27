import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    vueDevTools(),
  ],
  publicDir: fileURLToPath(new URL('public', import.meta.url)), // Đảm bảo ./public là đường dẫn đúng
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },

  build: {
    rollupOptions: {
      external: [
        '/img/Banner-ChuSan.png'
      ]
    }
  },
  server: {
    host: '0.0.0.0',
    port: 5174,
    strictPort: false,
    allowedHosts: ['keysport.sitilink.vn']
  }
})
