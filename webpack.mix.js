const mix = require('laravel-mix');

mix
  .js('resources/js/app.js', 'public/js')
  .css('resources/css/app.css', 'public/css')
  .styles(['resources/css/navbar.css'], 'public/css/navbar.css')
  .sourceMaps();