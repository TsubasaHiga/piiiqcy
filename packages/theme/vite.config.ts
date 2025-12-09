import { dirname } from 'path'
import { visualizer } from 'rollup-plugin-visualizer'
import { fileURLToPath } from 'url'
import { createLogger, defineConfig, loadEnv, UserConfig } from 'vite'
import FullReload from 'vite-plugin-full-reload'
import tsconfigPaths from 'vite-tsconfig-paths'

import { projectConfig } from '../../project.config'
import imagesOptimize from './integrations/imagesOptimize'
import wordpressDevInfo from './integrations/wordpressDevInfo'
import isProduction from './src/scripts/utils/isProduction'

// ESM __dirname equivalent
const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)

// Load environment variables from root .env
process.env = { ...process.env, ...loadEnv('', process.cwd()), ...loadEnv('', '../../') }

// Page-specific entry points
type SpecificPageInputListType = {
  [key: string]: string
}
const specificPageList = ['pageTop', 'pageAbout']
const specificPageInputList = specificPageList.reduce((acc: SpecificPageInputListType, page) => {
  acc[page] = `./src/scripts/${page}.ts`
  return acc
}, {})

// SCSS Logger - Mute specific warnings
const muteScssWarningList = [
  'mixed-decls',
  'legacy-js-api',
  'Sass @import rules are deprecated and will be removed in Dart Sass 3.0.0.'
]
const SCSS_Logger = {
  warn(message: string, options: { deprecation?: boolean }) {
    if (options.deprecation && muteScssWarningList.some((mute) => message.includes(mute))) return
  }
}

// Vite Logger - Mute specific warnings
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

// Theme name from project config
const THEME_NAME = projectConfig.theme.name

// Path configurations for different build modes
const PathList: PathType[] = [
  {
    mode: 'stg',
    distPath: './dist-stg',
    themePath: `/stg/wp-content/themes/${THEME_NAME}/`
  },
  {
    mode: 'prod',
    distPath: './dist',
    themePath: `/wp-content/themes/${THEME_NAME}/`
  }
] as const

const getPaths = (mode: string): PathType => {
  return PathList.find((path) => path.mode === mode) || (PathList.find((path) => path.mode === 'prod') as PathType)
}

// https://vitejs.dev/config/
const config = (mode: string): UserConfig => {
  const { distPath, themePath } = getPaths(mode)

  return {
    base: isProduction() ? '' : '/',
    root: '',
    server: {
      host: true,
      cors: true,
      strictPort: true,
      port: projectConfig.dev.port,
      hmr: {
        host: process.env.VITE_API_URL
      }
    },
    preview: {
      host: true,
      port: projectConfig.dev.port
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
          api: 'modern-compiler',
          loadPaths: [process.cwd()],
          additionalData: `
            @use "sass:map";
            @use "sass:math";
            @use "src/styles/abstracts" as *;
            $base-dir: '${themePath}';
          `,
          silenceDeprecations: ['import', 'global-builtin'],
          logger: SCSS_Logger
        } as Record<string, unknown>
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
    plugins: [
      tsconfigPaths(),
      FullReload([distPath + '/**/*.php'], { root: __dirname }),
      imagesOptimize(),
      wordpressDevInfo()
    ]
  }
}

export default defineConfig(({ mode }) => config(mode))
