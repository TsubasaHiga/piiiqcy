import path from 'path'
import { visualizer } from 'rollup-plugin-visualizer'
import { defineConfig, loadEnv, UserConfig } from 'vite'
import FullReload from 'vite-plugin-full-reload'
import tsconfigPaths from 'vite-tsconfig-paths'

// isProduction
const isProduction = process.env.NODE_ENV === 'production'

process.env = { ...process.env, ...loadEnv('', process.cwd()) }

// 特定のページ別のスタイルリスト
type SpecificPageInputListType = {
  [key: string]: string
}
const specificPageList = ['pageTop', 'pageAbout']
const specificPageInputList = specificPageList.reduce((acc: SpecificPageInputListType, page) => {
  acc[page] = `./src/scripts/${page}.ts`
  return acc
}, {})

// https://vitejs.dev/config/
const config = (mode: string): UserConfig => {
  const distPath = mode === 'stg' ? './dist-stg' : './dist'
  const themePath = mode === 'stg' ? '/stg/wp-content/themes/piiiqcy/' : '/wp-content/themes/piiiqcy/'

  return {
    base: isProduction ? '' : '/',
    root: '',
    server: {
      host: true,

      // required to load scripts from custom host
      cors: true,

      // we need a strict port to match on PHP side
      // change freely, but update in your functions.php to match the same port
      strictPort: true,
      port: 3000,

      hmr: {
        host: process.env.VITE_API_URL
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
          includePaths: [path.resolve(__dirname, 'src/styles')],
          additionalData:
            `
          @use "sass:map";
          @use "sass:math";
          @use "./src/styles/Foundation/_variables.scss" as *;
          @use "./src/styles/Foundation/_mixin.scss" as *;
          @use "./src/styles/Foundation/_functions.scss" as *;
            $base-dir: '` +
            themePath +
            `';
        `
        }
      }
    },
    build: {
      outDir: distPath + '/assets',
      assetsDir: './',
      emptyOutDir: mode === 'analyze' || mode === 'without-convert-images' ? false : true,
      manifest: true,
      rollupOptions: {
        input: {
          main: './src/scripts/main.ts',
          ...specificPageInputList
        },
        plugins: [
          mode === 'analyze' &&
            visualizer({
              open: true,
              filename: distPath + '/assets/stats.html',
              gzipSize: true
            })
        ]
      }
    },
    plugins: [tsconfigPaths(), FullReload([distPath + '/**/*.php'], { root: __dirname })]
  }
}

export default defineConfig(({ mode }) => config(mode))
