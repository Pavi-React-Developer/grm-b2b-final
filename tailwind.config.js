/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/Views/**/*.php",
    "./public/**/*.js",
    "./js/**/*.js"
  ],
  theme: {
    extend: {
      colors: {
        brand: {
          50:  '#fdf2f7',
          100: '#fce7f1',
          200: '#fbaed2',
          300: '#f784b7',
          400: '#f56b9c',
          500: '#F25996',
          600: '#e04481',
          700: '#c72e6b',
          800: '#a32457',
          900: '#831e46',
        }
      },
      fontFamily: {
        sans: ['Inter', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
        display: ['Outfit', 'Inter', 'sans-serif'],
        body: ['Inter', 'sans-serif']
      }
    },
  },
  plugins: [],
}
