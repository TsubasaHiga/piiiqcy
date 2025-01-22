import path from 'path'
import { visualizer } from 'rollup-plugin-visualizer'
import { createLogger, defineConfig, loadEnv, UserConfig } from 'vite'
import FullReload from 'vite-plugin-full-reload'
import tsconfigPaths from 'vite-tsconfig-paths'

import imagesOptimize from './integrations/imagesOptimize'
import isProduction from './src/scripts/utils/isProduction'

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

// scss logger
const muteScssWarningList = [
  'mixed-decls',
  'legacy-js-api',
  'Sass @import rules are deprecated and will be removed in Dart Sass 3.0.0.'
]
const SCSS_Logger = {
  warn(message: any, options: any) {
    // Mute warning for muteScssWarningList
    if (options.deprecation && muteScssWarningList.some((mute) => message.includes(mute))) return

    // List all other warnings
    // console.warn(`▲ [WARNING]: ${message}`)
  }
}

// VITE_Logger
const VITE_Logger = createLogger()

const muteWarnOnceList = ['it will remain unchanged to be resolved at runtime']
VITE_Logger.warnOnce = (msg, options) => {
  if (muteWarnOnceList.some((mute) => msg.includes(mute))) return
  return VITE_Logger.warnOnce(msg, options)
}

type PathType = {
  mode: string
  distPath: string
  themePath: string
}

// Path List
const PathList: PathType[] = [
  {
    mode: 'stg',
    distPath: './dist-stg',
    themePath: '/stg/wp-content/themes/piiiqcy/'
  },
  {
    mode: 'prod',
    distPath: './dist',
    themePath: '/wp-content/themes/piiiqcy/'
  }
] as const

// get path
const getPaths = (mode: string): PathType => {
  const path =
    PathList.find((path) => path.mode === mode) || (PathList.find((path) => path.mode === 'prod') as PathType)
  return path
}

// https://vitejs.dev/config/
const config = (mode: string): UserConfig => {
  const { distPath, themePath } = getPaths(mode)

  return {
    base: isProduction() ? '' : '/',
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
    esbuild: isProduction()
      ? {
          drop: ['debugger'],
          pure: ['console.log', 'console.info', 'console.table', 'console.time', 'console.timeEnd', 'console.trace']
        }
      : {},
    customLogger: VITE_Logger,
    css: {
      devSourcemap: !isProduction(),
      preprocessorOptions: {
        scss: {
          includePaths: [path.resolve(__dirname, 'src/styles')],
          additionalData: `
            @use "sass:map";
            @use "sass:math";
            @use "./src/styles/Foundation/_variables.scss" as *;
            @use "./src/styles/Foundation/_mixin.scss" as *;
            @use "./src/styles/Foundation/_functions.scss" as *;
            $base-dir: '${themePath}';
          `,
          logger: SCSS_Logger
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
    plugins: [tsconfigPaths(), FullReload([distPath + '/**/*.php'], { root: __dirname }), imagesOptimize()]
  }
}

export default defineConfig(({ mode }) => config(mode))
