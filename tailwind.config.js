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
                // Modern Purple to Blue Gradient
                primary: {
                    50: '#f3f0ff',
                    100: '#e9e1ff',
                    200: '#d4c4ff',
                    300: '#b197fc',
                    400: '#9775fa',
                    500: '#845ef7',
                    600: '#7950f2',
                    700: '#7048e8',
                    800: '#6741d9',
                    900: '#5f3dc4',
                    950: '#5028a7',
                },
                // Vibrant Accent Colors
                accent: {
                    50: '#fff5f5',
                    100: '#ffe3e3',
                    200: '#ffc9c9',
                    300: '#ffa8a8',
                    400: '#ff8787',
                    500: '#ff6b6b',
                    600: '#fa5252',
                    700: '#f03e3e',
                    800: '#e03131',
                    900: '#c92a2a',
                },
                // Modern Success Green
                success: {
                    50: '#ebfbee',
                    100: '#d3f9d8',
                    200: '#b2f2bb',
                    300: '#8ce99a',
                    400: '#69db7c',
                    500: '#51cf66',
                    600: '#40c057',
                    700: '#37b24d',
                    800: '#2f9e44',
                    900: '#2b8a3e',
                },
                // Dark Mode Support
                dark: {
                    50: '#f8f9fa',
                    100: '#f1f3f5',
                    200: '#e9ecef',
                    300: '#dee2e6',
                    400: '#ced4da',
                    500: '#adb5bd',
                    600: '#868e96',
                    700: '#495057',
                    800: '#343a40',
                    900: '#212529',
                },
            },
            fontFamily: {
                sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                heading: ['Space Grotesk', 'Plus Jakarta Sans', 'ui-sans-serif', 'system-ui'],
            },
            fontSize: {
                '2xs': ['0.625rem', { lineHeight: '0.875rem' }],
                '3xl': ['2rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.5rem', { lineHeight: '2.75rem' }],
                '5xl': ['3rem', { lineHeight: '3.25rem' }],
                '6xl': ['3.75rem', { lineHeight: '4rem' }],
                '7xl': ['4.5rem', { lineHeight: '4.75rem' }],
                '8xl': ['6rem', { lineHeight: '6.25rem' }],
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'shimmer': 'shimmer 2s linear infinite',
                'gradient': 'gradient 15s ease infinite',
                'slide-up': 'slideUp 0.5s ease-out forwards',
                'fade-in': 'fadeIn 0.5s ease-out forwards',
                'bounce-slow': 'bounce 2s ease-in-out infinite',
                'spin-slow': 'spin 3s linear infinite',
                'wiggle': 'wiggle 1s ease-in-out infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                    '33%': { transform: 'translateY(-10px) rotate(1deg)' },
                    '66%': { transform: 'translateY(-5px) rotate(-1deg)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                gradient: {
                    '0%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                    '100%': { backgroundPosition: '0% 50%' },
                },
                slideUp: {
                    'from': { opacity: '0', transform: 'translateY(20px)' },
                    'to': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeIn: {
                    'from': { opacity: '0' },
                    'to': { opacity: '1' },
                },
                wiggle: {
                    '0%, 100%': { transform: 'rotate(-3deg)' },
                    '50%': { transform: 'rotate(3deg)' },
                },
            },
            backdropBlur: {
                xs: '2px',
                '3xl': '64px',
            },
            backdropSaturate: {
                25: '.25',
                200: '2',
            },
            boxShadow: {
                'glow': '0 0 30px rgba(132, 94, 247, 0.3)',
                'glow-lg': '0 0 60px rgba(132, 94, 247, 0.4)',
                'inner-glow': 'inset 0 0 20px rgba(132, 94, 247, 0.2)',
                '3xl': '0 35px 60px -15px rgba(0, 0, 0, 0.3)',
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                'gradient-mesh': 'url("data:image/svg+xml,%3Csvg width="100" height="100" xmlns="http://www.w3.org/2000/svg"%3E%3Cdefs%3E%3Cpattern id="a" patternUnits="userSpaceOnUse" width="20" height="20"%3E%3Crect width="1" height="1" fill="%239775fa" opacity="0.1"/%3E%3C/pattern%3E%3C/defs%3E%3Crect width="100" height="100" fill="url(%23a)"/%3E%3C/svg%3E")',
            },
            screens: {
                'xs': '475px',
                '3xl': '1920px',
            },
            transitionDuration: {
                '400': '400ms',
                '600': '600ms',
                '800': '800ms',
                '900': '900ms',
                '2000': '2000ms',
            },
            transitionTimingFunction: {
                'bounce-in': 'cubic-bezier(0.68, -0.55, 0.265, 1.55)',
                'bounce-out': 'cubic-bezier(0.175, 0.885, 0.32, 1.275)',
            },
            scale: {
                '102': '1.02',
                '98': '0.98',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
        require('flowbite-typography'),
    ],
}
