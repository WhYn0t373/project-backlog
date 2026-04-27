```js
const mix = require('laravel-mix');

/*
 * Build configuration
 * - JS bundles: app and vendor
 * - CSS: SASS compilation with PurgeCSS
 * - Full tree‑shaking via .extract()
 */
mix.js('resources/js/app.js', 'public/js')
   .js('resources/js/vendor.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css')
   .options({
       processCssUrls: false,
       postCss: [
           require('postcss-purgecss')({
               content: [
                   './resources/**/*.blade.php',
                   './resources/**/*.vue',
                   './resources/**/*.js',
               ],
               defaultExtractor: content => content.match(/[\w-/:]+(?<!:)/g) || []
           })
       ]
   })
   .extract(); // Enables full tree‑shaking of vendor libs
```