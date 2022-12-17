const mix = require('laravel-mix');
const tailwindcss = require('tailwindcss');

mix.setPublicPath('public')
   .sass('resources/sass/app.scss', 'public/css')
   .options({
       postCss: [tailwindcss('./tailwind.config.js')],
   })
   .version();
