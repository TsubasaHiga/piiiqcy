module.exports = {
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
    'css-mqpacker': {},
    cssnano: {
      autoprefixer: false
    },
    'css-declaration-sorter': {
      order: 'smacss'
    }
  }
}
