'use strict'

/**
 *
 * 初期設定（プラグイン読み込み、webpack設定、変数、入出力設定、環境依存設定など）
 *
 */

// プラグイン読み込み.
const autoprefixer = require('gulp-autoprefixer')
const browserSync = require('browser-sync').create()
const css = require('gulp-sass')
const cssnano = require('cssnano')
const cssDeclarationSorter = require('css-declaration-sorter')
const colors = require('colors')
const crypto = require('crypto')
const dateutils = require('date-utils')
const del = require('del')
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
const plumber = require('gulp-plumber')
const pngquant = require('imagemin-pngquant')
const postcss = require('gulp-postcss')
const sourcemaps = require('gulp-sourcemaps')
const webpack = require('webpack')
const webpackStream = require('webpack-stream')
const zip = require('gulp-zip')

// ファイル存在判定.
const isExistFile = file => {
  try {
    fs.statSync(file)
    return true
  } catch (err) {
    if (err.code === 'ENOENT') return false
  }
}

// 環境設定ファイルの読み込み.
const env = isExistFile('./env.json')
  ? JSON.parse(fs.readFileSync('./env.json', 'utf8'))
  : ''

// webpackの設定ファイルの読み込み.
const webpackConfig = require('./webpack.config')
const webpackConfigBuild = require('./webpack.production.config')

// json file check task.
const jsoncFileCeck = cb => {
  // サイト設定ファイルの読み込み.
  const siteSetting = isExistFile('./setting.json')
    ? JSON.parse(fs.readFileSync('./setting.json', 'utf8'))
    : ''

  if (env && siteSetting) {
    gulp
      .src([env.io.env, env.io.siteSetting])
      .pipe(jsonlint())
      .pipe(jsonlint.reporter())

    console.log('---------------------------'.green)
    console.log('json file check OK! Ready..'.bold.green)
    console.log('- OK: env.json'.cyan)
    console.log('- OK: setting.json'.cyan)
    console.log('---------------------------'.green)
  } else {
    console.log('------------------------------'.red)
    console.log('The json file cannot be read..'.bold.red)
    console.log('------------------------------'.red)
  }

  cb()
}

// BrowserSync - sync.
const sync = () => browserSync.init(env.browsersync)

// BrowserSync - reload.
const reload = cb => {
  browserSync.reload()
  cb()
}

// CleanImg.
const cleanImg = () => {
  return del(env.io.output.img + '**/*.{png,jpg,gif,svg}')
}

// Scss compile.
const scss = () => {
  return gulp
    .src(env.io.input.css + '**/*.scss')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
    .pipe(sourcemaps.init())
    .pipe(
      css({
        precision: 5,
        importer: packageImporter({
          extensions: ['.scss', '.css']
        })
      })
    )
    .pipe(autoprefixer({}))
    .pipe(
      postcss([
        mqpacker(),
        cssnano({ autoprefixer: false }),
        cssDeclarationSorter({
          order: 'smacss'
        })
      ])
    )
    .pipe(sourcemaps.write('/maps'))
    .pipe(gulp.dest(env.io.output.css))
    .pipe(gulpif(browserSync.active === true, browserSync.stream()))
}

// Img compressed.
const img = () => {
  return gulp
    .src(env.io.input.img + '**/*.{png,jpg,gif,svg}')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
    .pipe(newer(env.io.output.img)) // srcとdistを比較して異なるものだけ処理
    .pipe(
      imagemin([
        pngquant({
          quality: [0.5, 0.9],
          speed: 1,
          floyd: 0
        }),
        mozjpeg({
          quality: 85,
          progressive: true
        }),
        imagemin.svgo(),
        imagemin.optipng(),
        imagemin.gifsicle()
      ])
    )
    .pipe(gulp.dest(env.io.output.img))
}

// WebpackStream.
const js = () => {
  return gulp
    .src(env.io.input.js + '**/*.js')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err)
          this.emit('end')
        }
      })
    )
    .pipe(webpackStream(webpackConfig, webpack))
    .pipe(gulp.dest(env.io.output.js))
}

// WebpackStream build
const jsBuild = () => {
  return gulp
    .src(env.io.input.js + '**/*.js')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
    .pipe(webpackStream(webpackConfigBuild, webpack))
    .pipe(gulp.dest(env.io.output.js))
}

// Watch files.
const watch = () => {
  gulp.watch(env.io.input.css + '**/*.scss', scss)
  gulp.watch(env.io.input.img + '**/*', img)
  gulp.watch(env.io.input.js + '**/*.js', gulp.series(js, reload))
  gulp.watch(
    env.io.output.php + '**/*.php',
    { interval: 250 }, reload)
}

// 納品ディレクトリ作成
const genDir = (dirname, type) => {
  dirname = typeof dirname !== 'undefined' ? dirname : 'publish_data'
  const distname = 'dist'
  if (type === 'zip') {
    return gulp
      .src([
        distname + '/**/*',
        '!' + distname + '/**/maps',
        '!' + distname + '/**/*.map',
        '!' + distname + '/**/*.DS_Store',
        '!' + distname + '/**/*.LICENSE',
        '!' + distname + '/**/*Thumbs.db'
      ])
      .pipe(zip(dirname + '.zip'))
      .pipe(gulp.dest(env.publishDir))
      .pipe(
        notify({
          title: '納品データをZIP化しました 🗜',
          message: '出力先：' + env.publishDir + '/' + dirname + '.zip'
        })
      )
  } else {
    return gulp
      .src([
        distname + '/**/*',
        '!' + distname + '/**/maps',
        '!' + distname + '/**/*.map',
        '!' + distname + '/**/*.DS_Store',
        '!' + distname + '/**/*.LICENSE',
        '!' + distname + '/**/*Thumbs.db'
      ])
      .pipe(gulp.dest(dirname))
  }
}

// 書き出しタスク（production）
const genPublishDir = cb => {
  const dirname = 'dist-production'
  genDir(dirname, 'publish')
  cb()
}

// 納品タスク
const genZipArchive = cb => {
  // サイト設定ファイルの読み込み.
  const siteSetting = JSON.parse(fs.readFileSync('./setting.json', 'utf8'))

  // 納品ファイル作成
  const dt = new Date()
  const date = dt.toFormat('YYMMDD-HHMI')
  const dirname = 'publish__' + date + '__' + siteSetting.publishFileName
  genDir(dirname, 'zip')
  cb()
}

exports.default = gulp.series(jsoncFileCeck, gulp.parallel(watch, sync))
exports.json_check = jsoncFileCeck
exports.img_reset = gulp.series(cleanImg, img)
exports.development = gulp.series(jsoncFileCeck, scss, cleanImg, img, js)
exports.production = gulp.series(jsoncFileCeck, scss, cleanImg, img, jsBuild, genPublishDir, js)
exports.zip = gulp.series(jsoncFileCeck, scss, cleanImg, img, jsBuild, genZipArchive, js)
