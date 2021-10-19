module.exports = {
  env: {
    browser: true,
    es2021: true,
    node: true,
  },
  extends: ['plugin:vue/essential', 'airbnb-base'],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module',
  },
  plugins: ['vue'],
  rules: {
    'no-shadow': ['error', { allow: ['state'] }],
    'no-param-reassign': ['error', { ignorePropertyModificationsFor: ['state'] }],
    'import/extensions': ['error', 'ignorePackages', {
      js: 'never',
      mjs: 'never',
      jsx: 'never',
      vue: 'never',
    }],
    'vue/no-v-model-argument': ['off'],
  },
  settings: {
    'import/resolver': {
      alias: {
        map: [
          ['@', './resources/js'],
          ['@modules', './app/Modules'],
          ['@theme', './resources/themes/default'],
          ['@icons', './resources/img/icons'],
        ],
        extensions: ['.ts', '.js', '.jsx', '.json', '.vue'],
      },
    },
  },
  globals: {
    route: 'readonly',
  },
};
