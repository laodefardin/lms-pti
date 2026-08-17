import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Geist', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                teal: {
                    50: '#f0f8ff',
                    100: '#e0f0fe',
                    200: '#bae0fd',
                    300: '#7dc5fb',
                    400: 'var(--teal-light)',
                    500: 'var(--teal)',
                    600: 'var(--teal)',
                    700: 'var(--teal-dark)',
                    800: '#002f5e',
                    900: '#001e3b',
                    950: '#001328',
                }
            }
        },
    },

    plugins: [forms],
};
