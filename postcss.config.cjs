module.exports = {
  plugins: {
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
