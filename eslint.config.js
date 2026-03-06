const js = require("@eslint/js");
const globals = require("globals");

module.exports = [
  {
    ignores: ["**/*.min.js"]
  },
  js.configs.recommended,
  {
    files: ["resources/js/**/*.js"],
    languageOptions: {
      ecmaVersion: 2020,
      sourceType: "script",
      globals: {
        ...globals.browser,
        ...globals.jquery,
        wp: "readonly",
        ajaxurl: "readonly",
        jQuery: "readonly",
        $: "readonly"
      }
    },
    rules: {
      "no-var": "off",
      "prefer-const": "warn",
      "no-unused-vars": ["warn", { "args": "none" }]
    }
  }
];
