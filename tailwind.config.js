/** @type {import('tailwindcss').Config} */
module.exports = {
  prefix: 'envbar-',
  content: [
    './resources/views/**/*.blade.php',
    './src/**/*.php'
  ],
  theme: {
    extend: {
      borderWidth: {
        '6': '6px',
      }
    },
  },
  plugins: [],
}
