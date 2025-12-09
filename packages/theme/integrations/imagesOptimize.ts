import { exec, spawn } from 'child_process'
import path from 'path'
import { promisify } from 'util'
import type { Plugin } from 'vite'

import { logInfo } from '../../../helpers/logging.js'

// Name of the integration
const name = 'images-optimize'

// Promisify the exec function
const execPromise = promisify(exec)

// Root directory (monorepo root)
const rootDir = path.resolve(import.meta.dirname, '../../..')
const scriptPath = path.join(rootDir, 'scripts/convertImages.ts')

// Function to run the convertImages script
const runConvertImages = async (watch: boolean = false) => {
  logInfo({ title: name, message: 'Images converting...' })
  const startTime = Date.now()

  const watchFlag = watch ? '--watch' : ''
  if (watch) {
    const process = spawn(
      'node',
      ['--no-warnings=ExperimentalWarning', '--loader', 'ts-node/esm', scriptPath, watchFlag],
      {
        stdio: 'inherit',
        shell: true,
        cwd: rootDir
      }
    )

    process.on('close', (code) => {
      if (code !== 0) {
        console.error(`convertImages process exited with code ${code}`)
      }
    })
  } else {
    try {
      const { stdout, stderr } = await execPromise(
        `node --no-warnings=ExperimentalWarning --loader ts-node/esm ${scriptPath} ${watchFlag}`,
        { cwd: rootDir }
      )

      // Output the stdout and stderr
      if (stdout) console.log(stdout)

      // Output the stderr
      if (stderr) console.error(stderr)

      const endTime = Date.now()
      logInfo({ message: `✓ Images converted successfully in ${endTime - startTime}ms.` })
    } catch (error) {
      console.error('Error converting images:', error)
    }
  }
}

// imagesOptimize
const imagesOptimize = (): Plugin => {
  let isServeMode = false

  return {
    name,
    configureServer() {
      isServeMode = true
      runConvertImages(true)
    },
    closeBundle: async () => {
      // Run after Vite build completes (after emptyOutDir clears the directory)
      // Skip if in serve mode (configureServer already handles it)
      if (!isServeMode) {
        await runConvertImages()
      }
    }
  }
}

export default imagesOptimize
