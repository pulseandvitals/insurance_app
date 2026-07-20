import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    200: '#bfdbfe',
                    300: '#93c5fd',
                    400: '#5598e7',
                    500: '#2a78d6',
                    600: '#256abf',
                    700: '#1c5cab',
                    800: '#184f95',
                    900: '#104281',
                    950: '#0d366b',
                },
                status: {
                    good: '#0ca30c',
                    warning: '#fab219',
                    serious: '#ec835a',
                    critical: '#d03b3b',
                },
            },
        },
    },

    plugins: [forms],
};
