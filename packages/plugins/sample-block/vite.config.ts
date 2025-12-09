import react from '@vitejs/plugin-react'
import { copyFileSync, existsSync, mkdirSync } from 'fs'
import { resolve } from 'path'
import { defineConfig, Plugin } from 'vite'

// Plugin name (change this when creating a new plugin)
const PLUGIN_NAME = 'sample-block'

// Output directory in wp-plugins
const OUTPUT_DIR = resolve(__dirname, `../../../wp-plugins/${PLUGIN_NAME}`)

/**
 * Custom Vite plugin to copy PHP files to the output directory
 */
function copyPhpPlugin(): Plugin {
  return {
    name: 'copy-php',
    closeBundle() {
      // Ensure output directory exists
      if (!existsSync(OUTPUT_DIR)) {
        mkdirSync(OUTPUT_DIR, { recursive: true })
      }

      // Copy index.php
      const srcPhp = resolve(__dirname, 'index.php')
      const destPhp = resolve(OUTPUT_DIR, 'index.php')
      if (existsSync(srcPhp)) {
        copyFileSync(srcPhp, destPhp)
        console.log(`Copied: index.php -> ${destPhp}`)
      }

      // Copy block.json
      const srcBlockJson = resolve(__dirname, 'src/blocks/sample-block/block.json')
      const buildDir = resolve(OUTPUT_DIR, 'build')
      const destBlockJson = resolve(buildDir, 'block.json')

      // Ensure build directory exists
      if (!existsSync(buildDir)) {
        mkdirSync(buildDir, { recursive: true })
      }

      if (existsSync(srcBlockJson)) {
        copyFileSync(srcBlockJson, destBlockJson)
        console.log(`Copied: block.json -> ${destBlockJson}`)
      }
    }
  }
}

export default defineConfig({
  plugins: [react(), copyPhpPlugin()],
  define: {
    'process.env.NODE_ENV': JSON.stringify(process.env.NODE_ENV || 'production')
  },
  build: {
    outDir: `${OUTPUT_DIR}/build`,
    emptyOutDir: true,
    manifest: false,
    lib: {
      entry: resolve(__dirname, 'src/index.tsx'),
      name: 'SampleBlock',
      formats: ['iife'],
      fileName: () => 'index.js'
    },
    rollupOptions: {
      external: [
        'react',
        'react-dom',
        '@wordpress/blocks',
        '@wordpress/block-editor',
        '@wordpress/components',
        '@wordpress/i18n',
        '@wordpress/element'
      ],
      output: {
        globals: {
          react: 'React',
          'react-dom': 'ReactDOM',
          '@wordpress/blocks': 'wp.blocks',
          '@wordpress/block-editor': 'wp.blockEditor',
          '@wordpress/components': 'wp.components',
          '@wordpress/i18n': 'wp.i18n',
          '@wordpress/element': 'wp.element'
        },
        assetFileNames: (assetInfo) => {
          // Output CSS as style.css for WordPress block compatibility
          if (assetInfo.name?.endsWith('.css')) {
            return 'style.css'
          }
          return '[name][extname]'
        }
      }
    },
    cssCodeSplit: false
  }
})
