const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');
const forms = require('@tailwindcss/forms');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'class', // Usar la clase 'dark' para alternar entre temas claro y oscuro

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Paleta de colores personalizada para tema claro
                light: {
                    background: '#F3F4F6', // Fondo principal (gris claro)
                    card: '#FFFFFF', // Fondo de tarjetas (blanco puro)
                    text: '#111827', // Texto (gris oscuro para contraste)
                    border: '#D1D5DB', // Bordes (gris claro)
                    primary: '#3B82F6', // Azul para botones primarios
                    success: '#10B981', // Verde para éxito
                    warning: '#F59E0B', // Amarillo para advertencias
                    danger: '#EF4444', // Rojo para errores
                    tableRow: '#E5E7EB', // Fondo alternativo de filas
                },
                // Paleta de colores para tema oscuro (como en la imagen)
                dark: {
                    background: '#1E293B', // Fondo principal oscuro
                    card: '#334155', // Fondo de tarjetas oscuro
                    text: '#F8FAFC', // Texto claro
                    border: '#475569', // Bordes oscuros
                    primary: '#3B82F6', // Azul para botones primarios
                    success: '#22C55E', // Verde para éxito
                    warning: '#FACC15', // Amarillo para advertencias
                    danger: '#EF4444', // Rojo para errores
                },
            },
        },
    },

    plugins: [
        forms, // Plugin para formularios estilizados
    ],
};