/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Nunito', 'sans-serif'],
      },
      colors: {
        primary: '#1E44A3',
        secondary: '#F6F9FF',
        accentbg: '#F4F6FB',
        orange: '#EF3C04',
        blue: '#427EF4',
        disabled: '#B5B5B5',
        green: '#00893F',
      },
    },
  },
  plugins: [],
}

