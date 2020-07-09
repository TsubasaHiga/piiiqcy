'use strict'

/**
 *
 * 初期設定（プラグイン読み込み、webpack設定、変数、入出力設定、環境依存設定など）
 *
 */

// プラグイン読み込み
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
const path = require('path')
const plumber = require('gulp-plumber')
const pngquant = require('imagemin-pngquant')
const postcss = require('gulp-postcss')
const webpack = require('webpack')
const webpackStream = require('webpack-stream')
const zip = require('gulp-zip')

// ファイル存在判定
const isExistFile = file => {
  try {
    fs.statSync(file)
    return true
  } catch (err) {
    if (err.code === 'ENOENT') return false
  }
}

// 環境設定ファイルの読み込み
const setting = isExistFile('./setting.json')
  ? JSON.parse(fs.readFileSync('./setting.json', 'utf8'))
  : ''

// webpackの設定ファイルの読み込み
const webpackConfig = require('./webpack.config')
const webpackConfigBuild = require('./webpack.production.config')

// json file check task
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

// BrowserSync - sync
const sync = () => browserSync.init(setting.browsersync)

// BrowserSync - reload
const reload = cb => {
  browserSync.reload()
  cb()
}

// CleanImg
const cleanImg = () => {
  return del(setting.io.output.img + '**/*.{png,apng,jpg,gif,svg}')
}

// Clean garbage.
const cleanGarbage = () => {
  return del([
    setting.io.output.php + '/**/maps',
    setting.io.output.php + '/**/*.map',
    setting.io.output.php + '/**/*.DS_Store',
    setting.io.output.php + '/**/*.LICENSE',
    setting.io.output.php + '/**/*Thumbs.db'
  ])
}

// Scss compile
const scss = () => {
  return gulp
    .src(
      setting.io.input.css + '**/*.scss', {
        sourcemaps: true
      }
    )
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
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
    .pipe(
      gulp.dest(setting.io.output.css, {
        sourcemaps: '/maps'
      })
    )
    .pipe(gulpif(browserSync.active === true, browserSync.stream()))
}

// scssProduction compile.
const scssProduction = () => {
  return gulp
    .src(setting.io.input.css + '**/*.scss')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
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
    .pipe(gulp.dest(setting.io.output.css))
    .pipe(gulpif(browserSync.active === true, browserSync.stream()))
}
// Img compressed
const img = () => {
  return gulp
    .src(setting.io.input.img + '**/*.{png,apng,jpg,gif,svg}')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
    .pipe(newer(setting.io.output.img)) // srcとdistを比較して異なるものだけ処理
    .pipe(
      imagemin([
        pngquant(setting.pngquant),
        mozjpeg(setting.mozjpeg),
        imagemin.svgo(),
        imagemin.optipng(),
        imagemin.gifsicle()
      ])
    )
    .pipe(gulp.dest(setting.io.output.img))
}

// WebpackStream
const js = () => {
  return gulp
    .src(setting.io.input.js + '**/*.js')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
    .pipe(webpackStream(webpackConfig, webpack))
    .pipe(gulp.dest(setting.io.output.js))
}

// WebpackStream build
const jsBuild = () => {
  return gulp
    .src(setting.io.input.js + '**/*.js')
    .pipe(
      plumber({
        errorHandler: err => {
          console.log(err.messageFormatted)
          this.emit('end')
        }
      })
    )
    .pipe(webpackStream(webpackConfigBuild, webpack))
    .pipe(gulp.dest(setting.io.output.js))
}

// Watch files
const watch = () => {
  gulp.watch(setting.io.input.css + '**/*.scss', scss)
  gulp.watch(setting.io.input.img + '**/*', img)
  gulp.watch(setting.io.input.js + '**/*.js', gulp.series(js, reload))
  gulp.watch(setting.io.output.php + '**/*.php',
    { interval: 250 }, reload)
}

// 納品ディレクトリ作成
const genDir = (dirname, type) => {
  dirname = typeof dirname !== 'undefined' ? dirname : 'publish_data'
  const distname = 'dist'
  const userHome = process.env[process.platform === 'win32' ? 'USERPROFILE' : 'HOME']
  const publishDir = path.join(userHome, setting.publishDir)

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
      .pipe(gulp.dest(publishDir))
      .pipe(
        notify({
          title: '納品データをZIP化しました 🗜',
          message: '出力先：' + publishDir + '/' + dirname + '.zip'
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
  const siteSetting = JSON.parse(fs.readFileSync('./setting-site.json', 'utf8'))

  // 納品ファイル作成
  const dt = new Date()
  const date = dt.toFormat('YYMMDD-HHMI')
  const dirname = 'publish__' + date + '__' + siteSetting.publishFileName
  genDir(dirname, 'zip')
  cb()
}

exports.default = gulp.series(jsoncFileCeck, gulp.parallel(watch, sync))

exports.development = gulp.series(jsoncFileCeck, scss, cleanImg, img, js)

exports.production = gulp.series(jsoncFileCeck, scssProduction, cleanImg, img, jsBuild, cleanGarbage)

exports.checkJson = jsoncFileCeck
exports.garbage = cleanGarbage

exports.zip = gulp.series(jsoncFileCeck, scssProduction, cleanImg, img, jsBuild, genZipArchive)
exports.resetImg = gulp.series(cleanImg, img)
