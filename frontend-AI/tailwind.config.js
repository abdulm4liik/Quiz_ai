import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: 'media',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Arial', 'Helvetica', 'sans-serif'], // Set Arial as the primary sans-serif font
      },
      colors: {
        navy: {
          DEFAULT: '#4A6FA5', // Choose one of the lighter navy options here
          soft: '#3B5998',    // Option 1
          muted: '#4A6FA5',   // Option 2
          sky: '#5A7D9A',     // Option 3
          },
          beige: {
            DEFAULT: '#F5F5DC', // Primary beige
            light: '#FAF8EF',   // Very light beige for backgrounds
            dark: '#D3C8A3',    // Darker beige for text or accents
          },
      },
    },
  },
  plugins: [
    forms({
      strategy: 'class', // Optionally, use 'class' instead of 'base' to manually apply reset
    }),
  ],
}
