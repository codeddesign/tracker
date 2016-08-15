const elixir = require('laravel-elixir');

elixir.config.sourcemaps = false;

elixir(mix => {
    mix.rollup('cookietrap/index.js', 'public/js/trap.js');
});
