'use strict'

const autoprefixer = require('autoprefixer')
const browserSync = require('browser-sync').create()
const cssnano = require('cssnano')
const cssDeclarationSorter = require('css-declaration-sorter')
const colors = require('colors')
const crypto = require('crypto')
const dateutils = require('date-utils')
const del = require('del')
const Fiber = require('fibers')
const fs = require('fs')
const gulp = require('gulp')
const gulpif = require('gulp-if')
const imagemin = require('gulp-imagemin')
const jsonlint = require('gulp-jsonlint')
const mozjpeg = require('imagemin-mozjpeg')
const mqpacker = require('css-mqpacker')
const newer = require('gulp-newer')
const notify = require('gulp-notify')
const packageImporter = require('node-sass-package-importer')
const path = require('path')
const plumber = require('gulp-plumber')
const pngquant = require('imagemin-pngquant')
const postcss = require('gulp-postcss')
const sass = require('gulp-sass')
const webpack = require('webpack')
const webpackStream = require('webpack-stream')
const zip = require('gulp-zip')

sass.compiler = require('sass')

/**
 * isExistFile
 * @param {*} file
 */
const isExistFile = file => {
  try {
    fs.statSync(file)
    return true
  } catch (err) {
    if (err.code === 'ENOENT') return false
  }
}

// 環境設定ファイルの読み込み
const setting = isExistFile('./setting.json') ? JSON.parse(fs.readFileSync('./setting.json', 'utf8')) : ''

// webpackの設定ファイルの読み込み
const webpackConfig = require('./webpack.config')
const webpackConfigBuild = require('./webpack.production.config')

/**
 * jsoncFileCeck
 * @param {function} cb
 */
const jsoncFileCeck = cb => {
  if (setting) {
    gulp
      .src([setting.io.setting])
      .pipe(jsonlint())
      .pipe(jsonlint.reporter())

    console.log('---------------------------'.green)
    console.log('json file check OK! Ready..'.bold.green)
    console.log('- OK: setting.json'.cyan)
    console.log('---------------------------'.green)
  } else {
    console.log('------------------------------'.red)
    console.log('The json file cannot be read..'.bold.red)
    console.log('------------------------------'.red)
  }

  cb()
}

/**
 * sync
 */
const sync = () => browserSync.init(setting.browsersync)

/**
 * reload
 * @param {function} cb
 */
const reload = cb => {
  browserSync.reload()
  cb()
}

/**
 * cleanImg
 */
const cleanImg = () => del(setting.io.output.img + '**/*.{png,apng,jpg,gif,svg,webp}')

/**
 * cleanGarbage
 */
const cleanGarbage = () => {
  return del([
    setting.io.output.php + '/**/maps',
    setting.io.output.php + '/**/*.map',
    setting.io.output.php + '/**/*.DS_Store',
    setting.io.output.php + '/**/*.LICENSE',
    setting.io.output.php + '/**/*Thumbs.db'
  ])
}

/**
 * scss
 */
const scss = () => {
  return gulp
    .src(setting.io.input.css + '**/*.scss', { sourcemaps: true })
    .pipe(
      sass({ fiber: Fiber, outputStyle: 'compressed' })
        .on('error', sass.logError)
    )
    .pipe(
      postcss([
        autoprefixer(),
        mqpacker(),
        cssnano({ autoprefixer: false }),
        cssDeclarationSorter({ order: 'smacss' })
      ])
    )
    .pipe(gulp.dest(setting.io.output.css, { sourcemaps: '/maps' }))
    .pipe(gulpif(browserSync.active === true, browserSync.stream()))
}

/**
 * scssProduction
 */
const scssProduction = () => {
  return gulp
    .src(setting.io.input.css + '**/*.scss')
    .pipe(
      sass({ fiber: Fiber, outputStyle: 'compressed' })
        .on('error', sass.logError)
    )
    .pipe(
      postcss([
        autoprefixer(),
        mqpacker(),
        cssnano({ autoprefixer: false }),
        cssDeclarationSorter({ order: 'smacss' })
      ])
    )
    .pipe(gulp.dest(setting.io.output.css))
    .pipe(gulpif(browserSync.active === true, browserSync.stream()))
}

/**
 * getImageLists
 * @param {boolean} onlyManual
 */
const getImageLists = onlyManual => {
  // defaultLists
  const defaultLists = setting.io.input.img + '**/*.{png,jpg,gif,svg}'

  // lists
  const lists = []

  if (onlyManual) {
    // push imageManualLists
    for (let i = 0; i < setting.imageManualLists.length; i = (i + 1) | 0) {
      lists.push(setting.io.input.img + setting.imageManualLists[i])
    }
  } else {
    // push defaultLists
    lists.push(defaultLists)

    // push ignore imageManualLists
    for (let i = 0; i < setting.imageManualLists.length; i = (i + 1) | 0) {
      lists.push('!' + setting.io.input.img + setting.imageManualLists[i])
    }
  }

  return lists
}

/**
 * img
 */
const img = () => {
  return gulp
    .src(getImageLists(false))
    .pipe(plumber({ errorHandler: err => { console.log(err.messageFormatted); this.emit('end') } }))
    .pipe(newer(setting.io.output.img))
    .pipe(
      imagemin([
        pngquant(setting.pngquant),
        mozjpeg(setting.mozjpeg),
        imagemin.svgo(setting.svgo),
        imagemin.gifsicle(setting.gifsicle)
      ])
    )
    .pipe(gulp.dest(setting.io.output.img))
}

/**
 * imgManual
 * 手動で圧縮率を設定する場合のタスクです。
 * 特定の画像の圧縮率を下げたい場合等で使用する事を想定しています。
 * 設定記述：setting.jsonのpngquantManualとmozjpegManual
 * @param {*} cb
 */
const imgManual = cb => {
  const imageLists = getImageLists(true)
  if (imageLists.length !== 0) {
    return gulp
      .src(imageLists)
      .pipe(plumber({ errorHandler: err => { console.log(err.messageFormatted); this.emit('end') } }))
      .pipe(newer(setting.io.output.img))
      .pipe(
        imagemin([
          pngquant(setting.pngquantManual),
          mozjpeg(setting.mozjpegManual)
        ])
      )
      .pipe(gulp.dest(setting.io.output.img))
  } else {
    cb()
  }
}

/**
 * js
 */
const js = () => {
  return gulp
    .src(setting.io.input.js + '**/*.js')
    .pipe(plumber({ errorHandler: err => { console.log(err.messageFormatted); this.emit('end') } }))
    .pipe(webpackStream(webpackConfig, webpack))
    .pipe(gulp.dest(setting.io.output.js))
}

/**
 * jsBuild
 */
const jsBuild = () => {
  return gulp
    .src(setting.io.input.js + '**/*.js')
    .pipe(plumber({ errorHandler: err => { console.log(err.messageFormatted); this.emit('end') } }))
    .pipe(webpackStream(webpackConfigBuild, webpack))
    .pipe(gulp.dest(setting.io.output.js))
}

/**
 * watch
 */
const watch = () => {
  gulp.watch(setting.io.input.css + '**/*.scss', scss)
  gulp.watch(setting.io.input.img + '**/*', img)
  gulp.watch(setting.io.input.js + '**/*.js', gulp.series(js, reload))
  gulp.watch(setting.io.output.php + '**/*.php', { interval: 250 }, reload)
}

exports.default = gulp.series(jsoncFileCeck, gulp.parallel(watch, sync))
exports.development = gulp.series(jsoncFileCeck, scss, cleanImg, img, js)
exports.production = gulp.series(jsoncFileCeck, scssProduction, cleanImg, img, jsBuild, cleanGarbage)
exports.checkJson = jsoncFileCeck
exports.garbage = cleanGarbage
exports.resetImg = gulp.series(cleanImg, img)
