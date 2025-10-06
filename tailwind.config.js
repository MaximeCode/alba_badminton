/** @type {import("tailwindcss").Config} */
module.exports = {
  // In the content array below, add all files that contain the classes using Tailwind CSS
  content: [
    "./wp-content/themes/alba_theme/**/*.{html,js,php}",
    // and all files that contain the classes using the flowbite plugin
    "./node_modules/flowbite/**/*.js",
  ],
  theme: {
    fontFamily: {
      personal: ["Poppins", "sans-serif"],
      sans: ["Helvetica", "Arial", "sans-serif"],
      serif: ["Times New Roman", "Georgia", "serif"],
      mono: [
        "Menlo",
        "Monaco",
        "Consolas",
        "Liberation Mono",
        "Courier New",
        "monospace",
      ],
      crimson: ["Crimson Pro", "serif"],
    },
    extend: {
      colors: {
        "primary-blue": "#1FA2DA",
        "secondary-blue": "#0A4E8F",
        "back-blue": "rgba(31,162,218,0.15)",
        "oct-rose": "#F26E9C",
      },
      boxShadow: {
        "box-dropdown": "0px 0px 17px 0px rgba(31,162,218,0.5);",
        card: "0px 10px 6px 0px rgba(31,162,218,0.5);",
      },
      typography: {
        DEFAULT: {
          css: {
            maxWidth: "100%",
          },
        },
      },
    },
    screens: {
      xs: "410px",
      sm: "640px",
      md: "768px",
      lg: "1024px",
      xl: "1280px",
      "2xl": "1536px",
      "3xl": "1600px",
    },
  },
  plugins: [require("flowbite/plugin"), require("@tailwindcss/typography")],
  root: true,
};
