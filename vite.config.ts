import path from 'path'
import { visualizer } from 'rollup-plugin-visualizer'
import { defineConfig, UserConfig } from 'vite'
import FullReload from 'vite-plugin-full-reload'
import tsconfigPaths from 'vite-tsconfig-paths'

// isProduction
const isProduction = process.env.NODE_ENV === 'production'

// https://vitejs.dev/config/
const config = (mode: string): UserConfig => {
  return {
    base: isProduction ? '/dist/' : '/',
    root: '',
    server: {
      // required to load scripts from custom host
      cors: true,

      // we need a strict port to match on PHP side
      // change freely, but update in your functions.php to match the same port
      strictPort: true,
      port: 3000,

      hmr: {
        host: 'localhost'
        //port: 443
      }
    },
    preview: {
      host: true,
      port: 3000
    },
    esbuild: isProduction
      ? {
          drop: ['debugger'],
          pure: ['console.log', 'console.info', 'console.table', 'console.time', 'console.timeEnd', 'console.trace']
        }
      : {},
    css: {
      devSourcemap: !isProduction,
      preprocessorOptions: {
        scss: {
          includePaths: [path.join(__dirname, 'src/styles')],
          additionalData: `
          @use "sass:map";
          @use "sass:math";
          @use "./src/styles/Foundation/_variables.scss" as *;
          @use "./src/styles/Foundation/_mixin.scss" as *;
          @use "./src/styles/Foundation/_functions.scss" as *;
        `
        }
      }
    },
    build: {
      outDir: './dist/assets',
      assetsDir: './',
      emptyOutDir: true,
      manifest: true,
      rollupOptions: {
        input: {
          main: './src/scripts/main.ts'
        },
        plugins: [
          mode === 'analyze' &&
            visualizer({
              open: true,
              filename: './dist/stats.html',
              gzipSize: true
            })
        ]
      }
    },
    plugins: [tsconfigPaths(), FullReload(['./dist/**/*.php'], { root: __dirname })]
  }
}

export default defineConfig(({ mode }) => config(mode))
