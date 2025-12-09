/**
 * Project Configuration
 *
 * This file defines project-wide settings that can be easily changed
 * when using this boilerplate for a new project.
 *
 * To rename the project, run: ./init.sh
 */

export const projectConfig = {
  /**
   * Project name (used for package names, docker containers, etc.)
   * @example 'my-awesome-site'
   */
  name: 'piiiqcy',

  /**
   * Project display name (used for documentation)
   * @example 'My Awesome Site'
   */
  displayName: 'piiiQcy',

  /**
   * Project description
   */
  description: 'WordPress theme boilerplate with monorepo structure',

  /**
   * Package scope for npm packages
   * @example '@my-org' or '@my-awesome-site'
   */
  scope: '@piiiqcy',

  /**
   * Theme configuration
   */
  theme: {
    /** Theme directory name (same as project name by default) */
    name: 'piiiqcy',

    /** Theme display name for WordPress */
    displayName: 'piiiQcy Theme',

    /** Theme description for WordPress */
    description: 'A custom WordPress theme',

    /** Theme author */
    author: 'Your Name',

    /** Theme version */
    version: '1.0.0'
  },

  /**
   * Docker configuration
   */
  docker: {
    /** Docker container prefix */
    prefix: 'piiiqcy',

    /** Docker network name */
    network: 'piiiqcy_network'
  },

  /**
   * Development server configuration
   * Note: Vite port is configured in .env (VITE_PORT)
   */
  dev: {}
} as const

export type ProjectConfig = typeof projectConfig
