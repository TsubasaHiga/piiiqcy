# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

piiiQcy is a WordPress theme boilerplate that follows WordPress coding standards. It uses Vite for modern frontend development with HMR support, Docker for local development environment, and integrates TypeScript, SCSS, and PHP.

## Development Commands

### Frontend Development

```bash
pnpm dev          # Start Vite dev server with HMR and image conversion watch
pnpm build        # TypeScript check + Vite production build + image conversion
pnpm build-stg    # Build for staging environment (outputs to dist-stg/)
pnpm analyze      # Build with bundle analysis visualization
```

### Linting & Formatting

```bash
pnpm lint:scripts               # ESLint for TypeScript files
pnpm lint:styles                # Stylelint for CSS/SCSS files
pnpm lint:php                   # PHP_CodeSniffer with WordPress-Extra standard
pnpm phpstan                    # PHPStan static analysis (level 5)
pnpm format                     # Prettier formatting
```

### Docker Environment

```bash
make first        # Initial Docker setup (create network and build)
make up           # Start Docker containers
make stop         # Stop Docker containers
make down         # Stop and remove containers with volumes
make wpinstall    # Install WordPress with default plugins
make dbdump       # Export database to dump.sql
```

### Other Commands

```bash
pnpm archive      # Build and create distribution archive
pnpm preview      # Build and preview production build
```

## Architecture

### Directory Structure

- `src/` - Frontend source code
  - `scripts/` - TypeScript entry points and modules
  - `styles/` - SCSS with Foundation/Components/Layouts/Pages/Utilities structure
  - `images/` - Source images (processed during build)
- `dist/` - WordPress theme (production build output + PHP templates)
- `dist-stg/` - Staging build output
- `scripts/` - Build utility scripts (image conversion, archive generation)
- `app/WordPress/` - WordPress core files
- `wp-plugins/` - Custom WordPress plugins

### Frontend Entry Points

Entry points are defined in `vite.config.ts`:

- `main.ts` - Global scripts and styles
- `pageTop.ts`, `pageAbout.ts` - Page-specific bundles

### TypeScript Path Aliases

Defined in `tsconfig.json`:

- `@/*` → `src/scripts/*`
- `@components/*`, `@modules/*`, `@pages/*`, `@utils/*`, etc.

### SCSS Structure

SCSS follows FLOCSS-like organization with global imports via `vite.config.ts`:

- `abstracts/` - Variables, mixins, functions, design tokens
- `Components/` - Reusable UI components (c-button, c-post-item, etc.)
- `Layouts/` - Layout structures
- `Pages/` - Page-specific styles (loaded via entry points, not globally)
- `Projects/` - Shared project styles (loaded globally)
- `Utilities/` - Utility classes

### PHP Theme Structure (in `dist/`)

- `functions.php` - Main theme functions (loads inc/ and lib/)
- `inc/` - Core includes (Vite integration, constants, environment)
- `lib/` - Theme functionality (init, cleanup, custom functions)
- `template-parts/` - Reusable PHP template parts

## Key Technologies

- **Frontend**: TypeScript, SCSS, Vite
- **Backend**: PHP with WordPress Coding Standards
- **Utilities**: umaki, dayjs
- **Environment**: Node.js v23.4.0, pnpm v9.15.4, Docker, Composer

## Coding Standards

### Commit Messages

Follow conventional commits with emoji from [gitmoji.dev](https://gitmoji.dev/):

```
<type>: <emoji> #<issue> <title>
```

Types: feat, fix, docs, style, refactor, test, chore, build, ci, perf, revert

### Versioning

Follows [Semantic Versioning](https://semver.org/):

- Major: Breaking changes
- Minor: Backward-compatible features
- Patch: Bug fixes
- Beta: `Ver1.0.0-beta.x`

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
