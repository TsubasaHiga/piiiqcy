# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

piiiQcy is a WordPress theme boilerplate with **monorepo structure** using pnpm workspaces. It allows simultaneous development of themes and custom plugins. The project uses Vite for modern frontend development with HMR support, Docker for local development environment, and integrates TypeScript, SCSS, and PHP.

## Monorepo Structure

```
piiiqcy/
├── packages/
│   ├── theme/              # WordPress theme package
│   │   ├── src/            # Frontend source code
│   │   ├── dist/           # Build output (WP theme)
│   │   └── vite.config.ts  # Theme Vite config
│   │
│   └── plugins/            # Custom plugins
│       └── sample-block/   # Sample block plugin
│           ├── src/        # Plugin source
│           └── vite.config.ts
│
├── wp-plugins/             # External + built plugins (mounted to WP)
├── project.config.ts       # Project-wide configuration
└── pnpm-workspace.yaml     # Workspace definition
```

## Development Commands

### Theme Development

```bash
pnpm dev          # Start theme Vite dev server with HMR
pnpm build        # TypeScript check + production build + image conversion
pnpm build-stg    # Build for staging environment (outputs to dist-stg/)
pnpm analyze      # Build with bundle analysis visualization
```

### Plugin Development

```bash
pnpm dev:plugins           # Watch all plugins
pnpm build:plugins         # Build all plugins
pnpm dev:all               # Develop theme AND plugins simultaneously
pnpm build:all-packages    # Build theme AND all plugins
```

### Package-specific Commands

```bash
# Run command for specific package
pnpm --filter @piiiqcy/theme dev
pnpm --filter @piiiqcy/sample-block build
```

### Linting & Formatting

```bash
pnpm lint:scripts          # ESLint for TypeScript files
pnpm lint:styles           # Stylelint for CSS/SCSS files
pnpm lint:php              # PHP_CodeSniffer with WordPress-Extra standard
pnpm phpstan               # PHPStan static analysis (level 5)
pnpm format                # Prettier formatting
```

### Docker Environment

```bash
make setup        # Full setup: composer + docker + wordpress (recommended)
make up           # Start Docker containers
make stop         # Stop Docker containers
make down         # Stop and remove containers with volumes
make dbdump       # Export database to dump.sql
```

### Project Management

```bash
./init.sh             # Interactively rename the project (no deps required)
```

## Architecture

### Directory Structure

- `packages/theme/src/` - Theme frontend source code
  - `scripts/` - TypeScript entry points and modules
  - `styles/` - SCSS with FLOCSS-like structure
  - `images/` - Source images (processed during build)
- `packages/theme/dist/` - WordPress theme (build output + PHP templates)
- `packages/plugins/` - Custom plugin sources
- `wp-plugins/` - WordPress plugins directory (external + built plugins)
- `scripts/` - Build utility scripts
- `app/WordPress/` - WordPress core files

### Frontend Entry Points

Entry points are defined in `packages/theme/vite.config.ts`:

- `main.ts` - Global scripts and styles
- `pageTop.ts`, `pageAbout.ts` - Page-specific bundles

### TypeScript Path Aliases

Defined in `packages/theme/tsconfig.json`:

- `@/*` → `src/scripts/*`
- `@components/*`, `@modules/*`, `@pages/*`, `@utils/*`, etc.

### SCSS Structure

SCSS follows FLOCSS-like organization with global imports via `vite.config.ts`:

- `abstracts/` - Variables, mixins, functions, design tokens
- `Components/` - Reusable UI components
- `Layouts/` - Layout structures
- `Pages/` - Page-specific styles
- `Projects/` - Shared project styles
- `Utilities/` - Utility classes

### PHP Theme Structure (in `packages/theme/dist/`)

- `functions.php` - Main theme functions
- `inc/` - Core includes (Vite integration, constants)
- `lib/` - Theme functionality (init, cleanup, custom functions)
- `template-parts/` - Reusable PHP template parts

### Plugin Build Output

Plugins are built to `wp-plugins/<plugin-name>/`:

```
wp-plugins/
├── sample-block/        # Built from packages/plugins/sample-block/
│   ├── build/
│   │   ├── index.js
│   │   ├── style.css
│   │   └── block.json
│   └── index.php
└── wordpress-seo/       # External plugin
```

## Key Technologies

- **Frontend**: TypeScript, SCSS, Vite
- **Backend**: PHP with WordPress Coding Standards
- **Package Manager**: pnpm (workspaces)
- **Utilities**: umaki
- **Environment**: Node.js v23.4.0, pnpm v9.15.4, Docker, Composer

## Project Configuration

The `project.config.ts` file contains project-wide settings:

```typescript
export const projectConfig = {
  name: 'piiiqcy',           // Project name
  scope: '@piiiqcy',         // npm scope
  theme: { ... },            // Theme settings
  docker: { ... },           // Docker settings
  dev: { port: 3000, ... },  // Dev server settings
}
```

## Coding Standards

### Commit Messages

Follow conventional commits with emoji from [gitmoji.dev](https://gitmoji.dev/):

```
<type>: <emoji> #<issue> <title>
```

Types: feat, fix, docs, style, refactor, test, chore, build, ci, perf, revert

### PHP

- WordPress Coding Standards via phpcs
- PHPStan level 5 with phpstan-wordpress extension

### TypeScript/JavaScript

- ESLint with TypeScript plugins
- Import sorting via eslint-plugin-simple-import-sort
- Strict TypeScript configuration

### SCSS

- Stylelint with standard-scss, recess-order, and Prettier configs

## URLs

- WordPress: http://localhost:8000
- Admin: http://localhost:8000/wp-admin/
- phpMyAdmin: http://localhost:8080
- Vite Dev Server: http://localhost:3000
