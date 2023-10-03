import {fileURLToPath, URL} from 'node:url'

import {defineConfig} from 'vite'
import vue from '@vitejs/plugin-vue'
import * as path from 'path'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./resources/js', import.meta.url))
    }
  },
  build: {
    manifest: true,
    lib: {
      entry: path.resolve(__dirname, 'resources/js/main.ts'),
      name: 'StackTraceInertia',
      fileName: format => `inertia.${format}.js`,
    },
    rollupOptions: {
      external: ['vue', '@inertiajs/vue3'],
      output: {
        globals: {
          vue: 'Vue',
          '@inertiajs/vue3': 'Inertia',
        }
      }
    }
  }
})
