import { exec, spawn } from 'child_process'
import { promisify } from 'util'
import type { Plugin } from 'vite'

import { logInfo } from '../helpers/logging.js'

// Name of the integration
const name = 'images-optimize'

// Promisify the exec function
const execPromise = promisify(exec)

// Function to run the convertImages script
const runConvertImages = async (watch: boolean = false) => {
  logInfo({ title: name, message: 'Images converting...' })
  const startTime = Date.now()

  const watchFlag = watch ? '--watch' : ''
  if (watch) {
    const process = spawn(
      'yarn',
      ['node', '--no-warnings=ExperimentalWarning --loader', 'ts-node/esm', 'scripts/convertImages.ts', watchFlag],
      {
        stdio: 'inherit',
        shell: true
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
        `yarn node --no-warnings=ExperimentalWarning --loader ts-node/esm scripts/convertImages.ts ${watchFlag}`
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
  return {
    name,
    buildStart: async () => {
      await runConvertImages()
    },
    configureServer() {
      runConvertImages(true)
    }
  }
}

export default imagesOptimize
