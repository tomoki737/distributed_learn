const mix = require('laravel-mix');

 mix.ts('resources/js/app.ts', 'public/js').vue()
 .postCss('resources/css/app.css', 'public/css');
