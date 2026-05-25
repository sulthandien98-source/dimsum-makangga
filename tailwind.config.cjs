const defaultTheme = require('tailwindcss/defaultTheme');
const forms = require('@tailwindcss/forms');

module.exports = {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};