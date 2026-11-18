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
                sans: ['"Plus Jakarta Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
            },

            // Palet tema Spark (nilainya sama dengan variabel :root di resources/css/app.css)
            colors: {
                spark: {
                    DEFAULT: '#0b3321',   // hijau tua: sidebar, tombol utama, kartu gelap
                    dark: '#0b3321',
                    'dark-2': '#12402b',  // hover / item aktif sidebar
                    darker: '#072417',    // hover tombol utama
                    lime: '#b6f500',      // aksen utama
                    'lime-soft': '#ecfccb',
                    'lime-faint': '#f5fde4',
                    bg: '#f5f5f3',        // latar halaman
                    text: '#111c16',
                    muted: '#6b7a72',
                },
            },

            boxShadow: {
                spark: '0 1px 2px rgb(11 51 33 / 0.04), 0 6px 18px rgb(11 51 33 / 0.04)',
            },
        },
    },

    plugins: [forms],
};
