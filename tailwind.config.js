/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./**/*.php",         // detecta clases en todos los archivos PHP del tema
    "./assets/**/*.js",   // si usas JS personalizado
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
