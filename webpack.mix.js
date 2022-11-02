const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
  .js('resources/js/app.js', 'public/js')
  .js('packages/blackshot/CoinMarketSdk/Portfolio/charts.js', 'public/js/portfolio-charts.js')
  .sass('resources/scss/app.scss', 'public/css')
  .sass('resources/source/src/sass/main.sass', 'public/css')
  .sass('resources/source/src/sass/global/dialog.sass', 'public/css')
  .version()
