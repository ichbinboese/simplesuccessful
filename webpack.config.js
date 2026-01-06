// webpack.config.js
const Encore  = require('@symfony/webpack-encore');
const path    = require('path');
const webpack = require('webpack'); // <-- WICHTIG: hinzufügen!
require('dotenv').config();

if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  // Copy local video assets to /public/build/videos
  .copyFiles({
    from: './assets/videos',
    to: 'videos/[path][name].[ext]'
  })

  // Entries
  .addEntry('app',  './assets/app.js')           // Basis/Onepager
  .addEntry('auth', './assets/auth/auth.js')     // Login/Forgot
  .addEntry('admin', './assets/admin/index.js')  // Admin SPA
  .addEntry('video_marketing', './assets/video/marketing.js')
  .addEntry('video_marketing_explain', './assets/video/marketing_explain.js')
  .addEntry('video_product', './assets/video/product.js')
  .addEntry('public_watch', './assets/public/watch.js')

  // Vue 3
  .enableVueLoader(() => {}, { version: 3 })

  // Alias, damit "@/..." auf ./assets zeigt
  .addAliases({ '@': path.resolve(__dirname, 'assets') })

  // Optional: Tailwind/PostCSS
  .enablePostCssLoader()

  .splitEntryChunks()

    // enables the Symfony UX Stimulus bridge (used in assets/stimulus_bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()
  .enableSourceMaps(!Encore.isProduction())
  .enableVersioning(Encore.isProduction())

  // Vue Feature-Flags (verhindert Warnungen & verbessert Tree-Shaking)
  .addPlugin(new webpack.DefinePlugin({
    __VUE_OPTIONS_API__: JSON.stringify(true),
    __VUE_PROD_DEVTOOLS__: JSON.stringify(false),
    __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(false),
    'process.env.VIDEO_PASS_LOCKED': JSON.stringify(process.env.VIDEO_PASS_LOCKED || 'VIDNOTREADY'),
    'process.env.VIDEO_PASS_PRODUCT': JSON.stringify(process.env.VIDEO_PASS_PRODUCT || 'Simple!#AK'),
  }))
;

module.exports = Encore.getWebpackConfig();
