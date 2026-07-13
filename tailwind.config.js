module.exports = {
  content: [
    './**/*.php',
    './assets/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: '#8B5CF6',
        secondary: '#22D3EE',
        accent: '#D4AF37',
        background: '#070A13',
        surface: '#0F172A',
        card: '#111827',
        muted: '#94A3B8',
        border: 'rgba(255,255,255,0.10)',
      },
      fontFamily: {
        sans: ['ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', '"Segoe UI"', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
      },
    },
  },
  plugins: [],
};
