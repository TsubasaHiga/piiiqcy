/**
 * Project Rename Script
 *
 * This script helps you rename the project to use a different name.
 * It updates all necessary files including package.json files,
 * docker-compose.yml, .env, and other configuration files.
 *
 * Usage:
 *   pnpm rename-project
 *
 * The script will prompt you for the new project name and update
 * all relevant files automatically.
 */

import { existsSync, readdirSync, readFileSync, statSync, writeFileSync } from 'fs'
import { join, resolve } from 'path'
import * as readline from 'readline'

const ROOT_DIR = resolve(__dirname, '..')

interface RenameConfig {
  oldName: string
  newName: string
  oldScope: string
  newScope: string
  oldDisplayName: string
  newDisplayName: string
}

/**
 * Read project.config.ts and extract current values
 */
function getCurrentConfig(): { name: string; scope: string; displayName: string } {
  const configPath = join(ROOT_DIR, 'project.config.ts')
  const content = readFileSync(configPath, 'utf-8')

  const nameMatch = content.match(/name:\s*['"]([^'"]+)['"]/)
  const scopeMatch = content.match(/scope:\s*['"]([^'"]+)['"]/)
  const displayNameMatch = content.match(/displayName:\s*['"]([^'"]+)['"]/)

  return {
    name: nameMatch?.[1] || 'piiiqcy',
    scope: scopeMatch?.[1] || '@piiiqcy',
    displayName: displayNameMatch?.[1] || 'piiiQcy'
  }
}

/**
 * Create readline interface for user input
 */
function createReadline(): readline.Interface {
  return readline.createInterface({
    input: process.stdin,
    output: process.stdout
  })
}

/**
 * Ask user a question and return the answer
 */
function askQuestion(rl: readline.Interface, question: string): Promise<string> {
  return new Promise((resolve) => {
    rl.question(question, (answer) => {
      resolve(answer.trim())
    })
  })
}

/**
 * Replace all occurrences in a file
 */
function replaceInFile(filePath: string, replacements: Array<[string | RegExp, string]>): void {
  if (!existsSync(filePath)) {
    console.log(`  Skipped (not found): ${filePath}`)
    return
  }

  let content = readFileSync(filePath, 'utf-8')
  let modified = false

  for (const [search, replace] of replacements) {
    const newContent = content.replace(search, replace)
    if (newContent !== content) {
      content = newContent
      modified = true
    }
  }

  if (modified) {
    writeFileSync(filePath, content)
    console.log(`  Updated: ${filePath}`)
  }
}

/**
 * Get all files matching a pattern recursively
 */
function getFiles(dir: string, pattern: RegExp): string[] {
  const files: string[] = []

  if (!existsSync(dir)) return files

  const items = readdirSync(dir)
  for (const item of items) {
    const fullPath = join(dir, item)
    const stat = statSync(fullPath)

    if (stat.isDirectory()) {
      // Skip node_modules, .git, dist directories
      if (!['node_modules', '.git', 'dist', 'dist-stg', 'vendor', 'app'].includes(item)) {
        files.push(...getFiles(fullPath, pattern))
      }
    } else if (pattern.test(item)) {
      files.push(fullPath)
    }
  }

  return files
}

/**
 * Main rename function
 */
async function renameProject(): Promise<void> {
  console.log('\n=== Project Rename Script ===\n')

  const current = getCurrentConfig()
  console.log('Current configuration:')
  console.log(`  Name: ${current.name}`)
  console.log(`  Scope: ${current.scope}`)
  console.log(`  Display Name: ${current.displayName}`)
  console.log('')

  const rl = createReadline()

  try {
    // Get new project name
    const newName = await askQuestion(rl, `Enter new project name (e.g., my-awesome-site): `)
    if (!newName) {
      console.log('Error: Project name is required')
      process.exit(1)
    }

    // Validate project name
    if (!/^[a-z][a-z0-9-]*$/.test(newName)) {
      console.log(
        'Error: Project name must start with a letter and contain only lowercase letters, numbers, and hyphens'
      )
      process.exit(1)
    }

    const newScope = `@${newName}`
    const newDisplayName = (await askQuestion(rl, `Enter display name (default: ${newName}): `)) || newName

    const config: RenameConfig = {
      oldName: current.name,
      newName,
      oldScope: current.scope,
      newScope,
      oldDisplayName: current.displayName,
      newDisplayName
    }

    console.log('\nNew configuration:')
    console.log(`  Name: ${config.newName}`)
    console.log(`  Scope: ${config.newScope}`)
    console.log(`  Display Name: ${config.newDisplayName}`)

    const confirm = await askQuestion(rl, '\nProceed with rename? (y/n): ')
    if (confirm.toLowerCase() !== 'y') {
      console.log('Cancelled')
      process.exit(0)
    }

    console.log('\nRenaming project...\n')

    // Update project.config.ts
    console.log('Updating project.config.ts...')
    replaceInFile(join(ROOT_DIR, 'project.config.ts'), [
      [new RegExp(`name:\\s*['"]${config.oldName}['"]`, 'g'), `name: '${config.newName}'`],
      [new RegExp(`scope:\\s*['"]${config.oldScope}['"]`, 'g'), `scope: '${config.newScope}'`],
      [new RegExp(`displayName:\\s*['"]${config.oldDisplayName}['"]`, 'g'), `displayName: '${config.newDisplayName}'`],
      [new RegExp(`prefix:\\s*['"]${config.oldName}['"]`, 'g'), `prefix: '${config.newName}'`],
      [new RegExp(`network:\\s*['"]${config.oldName}_network['"]`, 'g'), `network: '${config.newName}_network'`],
      // Theme section
      [new RegExp(`name:\\s*['"]${config.oldName}['"]`, 'g'), `name: '${config.newName}'`]
    ])

    // Update root package.json
    console.log('Updating package.json files...')
    replaceInFile(join(ROOT_DIR, 'package.json'), [
      [new RegExp(`"name":\\s*"${config.oldName}"`, 'g'), `"name": "${config.newName}"`],
      [new RegExp(config.oldScope, 'g'), config.newScope]
    ])

    // Update packages/theme/package.json
    replaceInFile(join(ROOT_DIR, 'packages/theme/package.json'), [[new RegExp(config.oldScope, 'g'), config.newScope]])

    // Update all plugin package.json files
    const pluginPackageJsons = getFiles(join(ROOT_DIR, 'packages/plugins'), /package\.json$/)
    for (const file of pluginPackageJsons) {
      replaceInFile(file, [[new RegExp(config.oldScope, 'g'), config.newScope]])
    }

    // Update docker-compose.yml
    console.log('Updating docker-compose.yml...')
    replaceInFile(join(ROOT_DIR, 'docker-compose.yml'), [
      [new RegExp(`${config.oldName}_network`, 'g'), `${config.newName}_network`]
    ])

    // Update .env (if PREFIX is defined there)
    console.log('Updating .env...')
    const envPath = join(ROOT_DIR, '.env')
    if (existsSync(envPath)) {
      let envContent = readFileSync(envPath, 'utf-8')
      if (!envContent.includes('PREFIX=')) {
        envContent += `\nPREFIX=${config.newName}\n`
        writeFileSync(envPath, envContent)
        console.log(`  Added PREFIX to .env`)
      } else {
        replaceInFile(envPath, [[new RegExp(`PREFIX=${config.oldName}`, 'g'), `PREFIX=${config.newName}`]])
      }
    }

    // Update block.json files in plugins
    console.log('Updating block.json files...')
    const blockJsonFiles = getFiles(join(ROOT_DIR, 'packages/plugins'), /block\.json$/)
    for (const file of blockJsonFiles) {
      replaceInFile(file, [[new RegExp(`"name":\\s*"${config.oldName}/`, 'g'), `"name": "${config.newName}/`]])
    }

    // Update theme's style.css (WordPress theme header)
    console.log('Updating WordPress theme files...')
    const styleCssPath = join(ROOT_DIR, 'packages/theme/dist/style.css')
    if (existsSync(styleCssPath)) {
      replaceInFile(styleCssPath, [
        [new RegExp(`Theme Name:\\s*${config.oldDisplayName}`, 'g'), `Theme Name: ${config.newDisplayName}`]
      ])
    }

    console.log('\n=== Rename Complete ===\n')
    console.log('Next steps:')
    console.log('1. Run `pnpm install` to update dependencies')
    console.log('2. If using Docker, recreate the network:')
    console.log(`   docker network rm ${config.oldName}_network`)
    console.log(`   docker network create ${config.newName}_network`)
    console.log('3. Rebuild the project: `pnpm build:all-packages`')
    console.log('')
  } finally {
    rl.close()
  }
}

// Run the script
renameProject().catch(console.error)
