module.exports = {
  module: {
    rules: [
      {
        test: /node_modules\/(.+)\.css$/,
        use: [
          {
            loader: 'style-loader',
          },
          {
            loader: 'css-loader',
            options: { url: false },
          },
        ],
        sideEffects: true
      }
    ]
  },

  // メインとなるJavaScriptファイル（エントリーポイント）
  entry: {
    'common': './src/js/common.js',
    'top': './src/js/top.js'
  },

  // ファイルの出力設定
  output: {
    //  出力ファイルのディレクトリ名
    path: `${__dirname}/public/wp-content/themes/${process.env.npm_package_config_themeName}/assets/js`,
    // 出力ファイル名
    filename: `[name].js`
  }
}
