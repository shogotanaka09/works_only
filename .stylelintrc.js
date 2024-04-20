module.exports = {
  extends: ['stylelint-config-recess-order', 'stylelint-config-standard-scss'],
  rules: {
    'at-rule-no-unknown': null,
    'scss/at-rule-no-unknown': true,
    'max-empty-lines': null,
    'declaration-colon-newline-after': null,
    'value-list-comma-newline-after': null,
    'font-family-no-duplicate-names': null,
    'no-duplicate-selectors': null,
    'no-descending-specificity': null,
    'scss/at-extend-no-missing-placeholder': null,
    'max-line-length': null,
    'selector-class-pattern': '^(.*)$',
    'color-function-notation': 'legacy',
    'font-family-no-missing-generic-family-keyword': [
      true,
      {
        "ignoreFontFamilies": "FontAwesome"
      }
    ]
  }
}
