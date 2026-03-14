import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                // Menggunakan font "Outfit" atau "Figtree" yang populer di Gen Z apps
                sans: ['Figtree', 'sans-serif'],
            },
            colors: {
                'talent': {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    500: '#22c55e', // Warna Hijau Utama
                    600: '#16a34a',
                    900: '#14532d',
                }
            },
            borderRadius: {
                '3xl': '1.5rem', // Lebih bulat = lebih modern
            },
            boxShadow: {
                'soft': '0 4px 20px -2px rgba(0, 0, 0, 0.05)',
                'glow': '0 0 15px rgba(34, 197, 94, 0.3)',
            }
        },
    },

    plugins: [forms],
};
