module.exports = {
  extends: [
    'eslint:recommended',
    'plugin:@typescript-eslint/recommended',
    'plugin:tailwindcss/recommended',
    'prettier'
  ],
  plugins: ['simple-import-sort'],
  env: {
    browser: true,
    es2021: true,
    node: true
  },
  parserOptions: {
    project: './tsconfig.json'
  },
  rules: {
    // tailwindcss
    'tailwindcss/no-custom-classname': 'off',

    // simple-import-sort
    'simple-import-sort/imports': 'error',

    // typescript
    '@typescript-eslint/no-explicit-any': 'off',
    '@typescript-eslint/no-non-null-assertion': 'off'
  }
}
