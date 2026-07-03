import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    light: '#E0F2FE',
                    DEFAULT: '#38BDF8',
                    dark: '#0284C7',
                },
                slate: {
                    800: '#1e293b',
                    900: '#0f172a',
                }
            }
        },
    },

    plugins: [forms],
};
