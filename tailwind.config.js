// tailwind.config.js
module.exports = {
  mode: 'jit', // if using v3+ it’s on by default
  content: [
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
    // add any other dirs containing tailwind classes at runtime
  ],
  theme: {
    extend: {
      animation: {
        'fade-in': 'fadeIn 0.8s ease-out forwards',
      },
      keyframes: {
        fadeIn: {
          '0%': { opacity: 0, transform: 'translateY(10px)' },
          '100%': { opacity: 1, transform: 'translateY(0)' },
        },
      },
    },
  },
  purge: {
    content: [
      './resources/views/**/*.blade.php',
      './resources/js/**/*.vue',
      // … other paths
    ],
    options: {
      safelist: [
        'translate-x-full',
        'translate-x-0',
        'opacity-0',
        'opacity-50',
        'pointer-events-none',
        'pointer-events-auto',
      ],
    },
  },
  // …
}
