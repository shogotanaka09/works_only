const webpackCommonConfig = require('./webpack.config.common.js')
const webpackMerge = require('webpack-merge')

module.exports = webpackMerge.merge(
  webpackCommonConfig,
  {
    mode: 'production',
    optimization: {
      runtimeChunk: 'single',
      splitChunks: {
        cacheGroups: {
          vendor: {
            test: /[\\/]node_modules[\\/]/,
            name: 'vendors',
            chunks: 'all'
          }
        }
      }
    }
  }
)
