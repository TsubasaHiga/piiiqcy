/**
 * Sync VERSION constant in wordpressDevInfo.ts with package.json version
 * This script is automatically run by `pnpm version` command
 */

import { readFileSync, writeFileSync } from 'fs'
import { dirname, join } from 'path'
import { fileURLToPath } from 'url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)

const ROOT_DIR = join(__dirname, '..')
const PACKAGE_JSON_PATH = join(ROOT_DIR, 'package.json')
const DEV_INFO_PATH = join(ROOT_DIR, 'packages/theme/integrations/wordpressDevInfo.ts')

// Read version from package.json
const packageJson = JSON.parse(readFileSync(PACKAGE_JSON_PATH, 'utf-8'))
const newVersion = packageJson.version

// Update VERSION constant in wordpressDevInfo.ts
const devInfoContent = readFileSync(DEV_INFO_PATH, 'utf-8')
const updatedContent = devInfoContent.replace(/const VERSION = '[^']*'/, `const VERSION = '${newVersion}'`)

if (devInfoContent !== updatedContent) {
  writeFileSync(DEV_INFO_PATH, updatedContent)
  console.log(`Updated VERSION to ${newVersion} in wordpressDevInfo.ts`)
} else {
  console.log(`VERSION already up to date (${newVersion})`)
}
