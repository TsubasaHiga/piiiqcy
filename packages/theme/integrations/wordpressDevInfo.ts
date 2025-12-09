import { renderFilled } from 'oh-my-logo'
import { loadEnv, Plugin } from 'vite'

// ANSI color codes
const cyan = (str: string) => `\x1b[36m${str}\x1b[0m`
const dim = (str: string) => `\x1b[2m${str}\x1b[0m`

// Fixed boilerplate info (not affected by init.sh rename)
// VERSION is auto-updated by `pnpm version` script
const LOGO_NAME = 'piiiQcy'
const VERSION = '1.0.0'
const GITHUB_REPO = 'TsubasaHiga/piiiqcy'

/**
 * Vite plugin to display WordPress development URLs after server start.
 * This helps avoid confusion since Vite's default URLs (localhost:3000)
 * are for the dev server, not the actual WordPress site.
 */
export default function wordpressDevInfo(): Plugin {
  return {
    name: 'wordpress-dev-info',
    configureServer(server) {
      const env = loadEnv('', process.cwd() + '/../..')
      const wpPort = env.WP_PORT || '8000'
      const pmaPort = env.PMA_PORT || '8080'

      server.httpServer?.once('listening', () => {
        setTimeout(async () => {
          await renderFilled(LOGO_NAME, {
            palette: 'sunset'
          })

          console.log(dim(`  WordPress Theme Boilerplate v${VERSION}`))
          console.log(dim(`  Repo: https://github.com/${GITHUB_REPO}`))
          console.log('')
          console.log(dim('  WordPress Dev Server'))
          console.log(cyan('  ───────────────────────────────────────────────'))
          console.log('')
          console.log(`  WordPress   ${cyan(`http://localhost:${wpPort}`)}`)
          console.log(`  Admin       ${cyan(`http://localhost:${wpPort}/wp-admin/`)}`)
          console.log(`  phpMyAdmin  ${cyan(`http://localhost:${pmaPort}`)}`)
          console.log('')
          console.log(cyan('  ───────────────────────────────────────────────'))
          console.log('')
          console.log(dim('  Vite URLs above are for HMR only'))
          console.log('')
        }, 100)
      })
    }
  }
}
