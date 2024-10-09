/** @type {import('tailwindcss').Config} */
module.exports = {
    // In the content array below, add all files that contain the classes using Tailwind CSS
    content: [
        "./wp-content/themes/alba_theme/**/*.{html,js,php}",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        fontFamily: {
            'personal': ['"Poppins"', 'sans-serif'],
            'sans': ['Helvetica', 'Arial', 'sans-serif'],
            'serif': ['Times New Roman', 'Georgia', 'serif'],
            'mono': ['Menlo', 'Monaco', 'Consolas', 'Liberation Mono', 'Courier New', 'monospace'],
        },
        extend: {
            colors: {
                'primary-blue': '#1FA2DA',
                'back-blue': 'rgba(31,162,218,0.15)',
            },
            boxShadow: {
                'box-dd': '0px 0px 17px 0px rgba(31,162,218,0.5);',
            },
        },
    },
    plugins: [
        require('flowbite/plugin')
    ],
    root: true,
}