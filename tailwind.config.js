/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'carre': {
          'purple': '#7C3AED',  // Violet principal de Carre Premium
          'purple-light': '#A855F7',
          'purple-dark': '#5B21B6',
          'amber': '#F59E0B',   // Ambre de Carre Premium
          'amber-light': '#FCD34D',
          'amber-dark': '#D97706'
        },
      },
      fontFamily: {
        'sans': ['Figtree', 'sans-serif'],
      },
    },
  },
  plugins: [],
}
