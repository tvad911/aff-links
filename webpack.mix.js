// webpack.mix.js

let mix = require('laravel-mix');

mix.js('assests/main.js', 'public')
	.sass('assests/main.scss', 'public');