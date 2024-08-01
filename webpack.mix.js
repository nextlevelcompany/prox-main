const mix = require('laravel-mix');
const NodePolyfillPlugin = require("node-polyfill-webpack-plugin");
const webpack = require("webpack");

mix.js('../prox_frontend/resources/js/app.js', 'public/js').vue({version: 2})
    // .js('../prox_frontend/resources/js/template.js', 'public/js/template.js')
    // .js('../prox_frontend/resources/js/layout.js', 'public/js/layout.js')
    .sass('../prox_frontend/resources/scss/bootstrap.scss', 'public/css/bootstrap.css')
    .sass('../prox_frontend/resources/scss/icons.scss', 'public/css/icons.css')
    .sass('../prox_frontend/resources/scss/app.scss', 'public/css/app.css')
    .sass('../prox_frontend/resources/scss/app.rtl.scss', 'public/css/app.rtl.css')
    .version();

mix.webpackConfig({
    resolve: {
        fallback: {
            stream: require.resolve("stream-browserify"),
            // http: require.resolve("stream-http"),
            // https: require.resolve("https-browserify"),
            // zlib: require.resolve("browserify-zlib"),
            // url: require.resolve("url"),
        },
        preferRelative: false,
    },
    plugins: [
        // new NodePolyfillPlugin(),
        // new webpack.ProvidePlugin({
        //     http: "stream-http",
        //     https: "https-browserify",
        // }),
    ],
}).sourceMaps()
