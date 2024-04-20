module.exports = {
  extends: 'eslint:recommended',
  env: {
    browser: true
  },
  parserOptions: {
    sourceType: 'module',
    ecmaVersion: 'latest'
  },
  rules: {
    semi: ['error', 'never'],
    quotes: ['error', 'single']
  }
}
