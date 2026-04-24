module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
    './resources/**/*.ts',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          100: '#f3f4f6',
          200: '#e5e7eb',
          300: '#d1d5db',
          400: '#9ca3af',
          500: '#6b7280',
          600: '#4b5563',
          700: '#374151',
          800: '#1f2937',
          900: '#111827',
        },
        contrast: {
          text: '#1e1e1e',
          bg: '#ffffff',
          alt: '#f4f4f4',
        },
      },
    },
  },
  plugins: [],
}