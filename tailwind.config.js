/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                // Soft Blue Scale - Calming & Professional
                primary: {
                    50: '#eff6ff',   // Lightest blue
                    100: '#dbeafe',  // Very light blue
                    200: '#bfdbfe',  // Light blue
                    300: '#93c5fd',  // Soft sky blue
                    400: '#60a5fa',  // Medium soft blue
                    500: '#3b82f6',  // Main soft blue - comfortable to eyes
                    600: '#2563eb',  // Slightly deeper blue
                    700: '#1d4ed8',  // Deep blue
                    800: '#1e40af',  // Navy blue
                    900: '#1e3a8a',  // Dark navy
                    950: '#172554',  // Darkest blue
                },
                // Soft Cyan/Teal for accents
                secondary: {
                    50: '#ecfeff',   // Lightest cyan
                    100: '#cffafe',  // Very light cyan
                    200: '#a5f3fc',  // Light cyan
                    300: '#67e8f9',  // Soft cyan
                    400: '#22d3ee',  // Medium cyan
                    500: '#06b6d4',  // Main cyan - soft and pleasant
                    600: '#0891b2',  // Deeper cyan
                    700: '#0e7490',  // Deep cyan
                    800: '#155e75',  // Dark cyan
                    900: '#164e63',  // Darker cyan
                    950: '#083344',  // Darkest cyan
                },
                // Soft Sky Blue for CTAs & Highlights
                accent: {
                    50: '#f0f9ff',   // Lightest sky
                    100: '#e0f2fe',  // Very light sky
                    200: '#bae6fd',  // Light sky
                    300: '#7dd3fc',  // Soft sky
                    400: '#38bdf8',  // Medium sky blue
                    500: '#0ea5e9',  // Main sky blue - vibrant but soft
                    600: '#0284c7',  // Deeper sky
                    700: '#0369a1',  // Deep sky
                    800: '#075985',  // Dark sky
                    900: '#0c4a6e',  // Darker sky
                    950: '#082f49',  // Darkest sky
                },
                // Success - Soft Green
                success: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',
                    600: '#16a34a',
                    700: '#15803d',
                    800: '#166534',
                    900: '#14532d',
                    950: '#052e16',
                },
                // Error - Soft Red
                error: {
                    50: '#fef2f2',
                    100: '#fee2e2',
                    200: '#fecaca',
                    300: '#fca5a5',
                    400: '#f87171',
                    500: '#ef4444',
                    600: '#dc2626',
                    700: '#b91c1c',
                    800: '#991b1b',
                    900: '#7f1d1d',
                    950: '#450a0a',
                },
                // Cool Gray Scale - Better for blue theme
                gray: {
                    50: '#f9fafb',
                    100: '#f3f4f6',
                    200: '#e5e7eb',
                    300: '#d1d5db',
                    400: '#9ca3af',
                    500: '#6b7280',
                    600: '#4b5563',
                    700: '#374151',
                    800: '#1f2937',
                    900: '#111827',
                    950: '#030712',
                },
                // Dark mode specific
                dark: {
                    bg: '#0f172a',
                    card: '#1e293b',
                    border: '#334155',
                    hover: '#334155',
                }
            },
            fontFamily: {
                // Modern font stack - Clean & Professional
                sans: ['Inter', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                display: ['Cabinet Grotesk', 'Inter', 'ui-sans-serif', 'system-ui'],
                mono: ['JetBrains Mono', 'ui-monospace', 'SFMono-Regular', 'Consolas', 'monospace'],
            },
            fontSize: {
                // More consistent sizing
                '2xs': ['0.625rem', { lineHeight: '0.875rem' }],
                'xs': ['0.75rem', { lineHeight: '1rem' }],
                'sm': ['0.875rem', { lineHeight: '1.25rem' }],
                'base': ['1rem', { lineHeight: '1.5rem' }],
                'lg': ['1.125rem', { lineHeight: '1.75rem' }],
                'xl': ['1.25rem', { lineHeight: '1.875rem' }],
                '2xl': ['1.5rem', { lineHeight: '2rem' }],
                '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
                '4xl': ['2.25rem', { lineHeight: '2.75rem' }],
                '5xl': ['3rem', { lineHeight: '3.5rem' }],
                '6xl': ['3.75rem', { lineHeight: '4.25rem' }],
                '7xl': ['4.5rem', { lineHeight: '5rem' }],
                '8xl': ['6rem', { lineHeight: '6.5rem' }],
            },
            animation: {
                // Subtle & smooth animations
                'fade-in': 'fadeIn 0.5s ease-out',
                'fade-up': 'fadeUp 0.5s ease-out',
                'fade-down': 'fadeDown 0.5s ease-out',
                'slide-in-right': 'slideInRight 0.3s ease-out',
                'slide-in-left': 'slideInLeft 0.3s ease-out',
                'scale-in': 'scaleIn 0.3s ease-out',
                'pulse-subtle': 'pulseSubtle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'spin-slow': 'spin 3s linear infinite',
                'bounce-subtle': 'bounceSubtle 1s infinite',
                'shimmer': 'shimmer 2s linear infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeUp: {
                    '0%': { opacity: '0', transform: 'translateY(10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                fadeDown: {
                    '0%': { opacity: '0', transform: 'translateY(-10px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInRight: {
                    '0%': { transform: 'translateX(100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                slideInLeft: {
                    '0%': { transform: 'translateX(-100%)', opacity: '0' },
                    '100%': { transform: 'translateX(0)', opacity: '1' },
                },
                scaleIn: {
                    '0%': { transform: 'scale(0.95)', opacity: '0' },
                    '100%': { transform: 'scale(1)', opacity: '1' },
                },
                pulseSubtle: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '0.8' },
                },
                bounceSubtle: {
                    '0%, 100%': { transform: 'translateY(0)' },
                    '50%': { transform: 'translateY(-5px)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
            },
            backdropBlur: {
                xs: '2px',
                sm: '4px',
                md: '12px',
                lg: '16px',
                xl: '24px',
                '2xl': '40px',
                '3xl': '64px',
            },
            boxShadow: {
                // Softer shadows
                'xs': '0 1px 2px 0 rgb(0 0 0 / 0.05)',
                'sm': '0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1)',
                'md': '0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1)',
                'lg': '0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1)',
                'xl': '0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1)',
                '2xl': '0 25px 50px -12px rgb(0 0 0 / 0.25)',
                'inner': 'inset 0 2px 4px 0 rgb(0 0 0 / 0.05)',
                'glow-sm': '0 0 15px rgba(59, 130, 246, 0.15)',
                'glow': '0 0 25px rgba(59, 130, 246, 0.2)',
                'glow-lg': '0 0 35px rgba(59, 130, 246, 0.25)',
            },
            backgroundImage: {
                // Modern gradients
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
                'gradient-conic': 'conic-gradient(from 180deg at 50% 50%, var(--tw-gradient-stops))',
                'mesh-light': `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%233b82f6' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
                'mesh-dark': `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
            },
            screens: {
                'xs': '475px',
                'sm': '640px',
                'md': '768px',
                'lg': '1024px',
                'xl': '1280px',
                '2xl': '1536px',
                '3xl': '1920px',
            },
            borderRadius: {
                'none': '0',
                'sm': '0.125rem',
                'DEFAULT': '0.25rem',
                'md': '0.375rem',
                'lg': '0.5rem',
                'xl': '0.75rem',
                '2xl': '1rem',
                '3xl': '1.5rem',
                'full': '9999px',
            },
            spacing: {
                '18': '4.5rem',
                '88': '22rem',
                '100': '25rem',
                '120': '30rem',
                '128': '32rem',
                '144': '36rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/typography'),
    ],
}
