import fs from 'fs'
import { createRequire } from 'module'
import path, { dirname } from 'path'
import { fileURLToPath } from 'url'

const require = createRequire(import.meta.url)
const archiver = require('archiver')
const dayjs = require('dayjs')

const __filename = fileURLToPath(import.meta.url)
const __dirname = dirname(__filename)
const packageJson = JSON.parse(fs.readFileSync(path.join(__dirname, '../package.json'), 'utf8'))

const zipArchive = async (targetDir: string, outputFileName: string): Promise<void> => {
  return new Promise((resolve, reject) => {
    const zipPath = `${outputFileName}.zip`
    const outputPath = path.join(__dirname, '../' + zipPath)
    const output = fs.createWriteStream(outputPath)

    const archive = archiver('zip', {
      zlib: { level: 9 }
    })

    output.on('close', () => {
      console.log(`${archive.pointer()} total bytes`)
      console.log(`Archive created: ${outputPath}`)
      resolve()
    })

    output.on('error', (err: Error) => {
      reject(err)
    })

    archive.on('error', (err: Error) => {
      reject(err)
    })

    archive.pipe(output)
    archive.glob('**', {
      cwd: targetDir
    })

    archive.finalize()
  })
}

const main = async () => {
  try {
    const date = dayjs().format('YYYYMMDD-HHmm')
    const projectName = packageJson.name.toUpperCase()
    await zipArchive('packages/theme/dist', `${projectName}_ProdBuild_${date}`)
  } catch (error) {
    console.error('Error creating archive:', error)
    process.exit(1)
  }
}

main()
