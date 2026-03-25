module.exports = {
  // Core formatting options
  printWidth: 100,
  tabWidth: 4,
  useTabs: false,
  semi: true,
  singleQuote: true,
  quoteProps: "as-needed",
  trailingComma: "es5",
  bracketSpacing: true,
  bracketSameLine: false,
  arrowParens: "always",
  endOfLine: "lf",

  // Plugin-specific options
  tailwindConfig: "./tailwind.config.js",
  tailwindAttributes: ["class", "className", "wire:class"],
  tailwindFunctions: ["clsx", "cn", "classNames"],

  // Plugin integrations
  plugins: ["prettier-plugin-tailwindcss"],

  // File-specific overrides
  overrides: [
    {
      files: "*.blade.php",
      options: {
        parser: "blade",
      },
    },
    {
      files: "*.json",
      options: {
        tabWidth: 2,
      },
    },
  ],
};