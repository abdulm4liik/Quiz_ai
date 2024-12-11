import defaultTheme from 'tailwindcss/defaultTheme'
import forms from '@tailwindcss/forms'

module.exports = {
  content: ['./index.html', './src/**/*.{vue,js,ts,jsx,tsx}'],
  darkMode: 'media',
  theme: {
    extend: {
      fontFamily: {
        sans: ['Arial', 'Helvetica', 'sans-serif'], 
      },
      colors: {
        navy: {
          DEFAULT: '#4A6FA5', 
          soft: '#3B5998',  
          muted: '#4A6FA5',   
          sky: '#5A7D9A',     
          },
          beige: {
            DEFAULT: '#F5F5DC', 
            light: '#FAF8EF',   
            dark: '#D3C8A3',  
          },
      },
    },
  },
  plugins: [
    forms({
      strategy: 'class',
    }),
  ],
}
