module.exports = {
  extends: ['eslint:recommended', 'plugin:@typescript-eslint/recommended', 'prettier'],
  plugins: ['simple-import-sort'],
  env: {
    browser: true,
    es2021: true,
    node: true
  },
  parserOptions: {
    project: ['./tsconfig.json', './packages/theme/tsconfig.json', './packages/plugins/*/tsconfig.json']
  },
  ignorePatterns: ['dist', 'dist-stg', 'node_modules', 'wp-plugins'],
  rules: {
    // simple-import-sort
    'simple-import-sort/imports': 'error',

    // typescript
    '@typescript-eslint/no-explicit-any': 'off',
    '@typescript-eslint/no-non-null-assertion': 'off',
    '@typescript-eslint/no-unused-expressions': 'off'
  }
}
