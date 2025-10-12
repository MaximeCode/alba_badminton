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
      typography: (theme) => ({
        DEFAULT: {
          css: {
            maxWidth: "100%",
            // IMPORTANT : Définir la couleur par défaut du texte
            color: theme("colors.gray.900"),

            p: {
              marginBottom: "2rem",
              textAlign: "justify",
              fontSize: "1.125rem",
              lineHeight: "1.75",
              color: theme("colors.gray.900"), // Texte noir
              "@screen md": {
                fontSize: "1.25rem",
              },
            },
            h2: {
              fontWeight: "400", // Normal, pas gras
              fontSize: "2rem",
              marginTop: "3rem",
              marginBottom: "1.5rem",
              color: "#000",
              textDecoration: "underline",
              textDecorationColor: "#1FA2DA",
              textUnderlineOffset: "0.125rem", // Soulignement plus proche
            },
            h3: {
              fontWeight: "400", // Normal, pas gras
              fontSize: "1.875rem",
              marginTop: "3rem",
              marginBottom: "1.5rem",
              color: "#000",
              textDecoration: "underline",
              textDecorationColor: "#1FA2DA",
              textUnderlineOffset: "0.125rem", // Soulignement plus proche
            },
            // Styles pour les liens
            a: {
              color: "#1FA2DA",
              textDecoration: "underline",
              fontWeight: "500",
              "&:hover": {
                color: "#0A4E8F", // secondary-blue au hover
              },
            },
            // Retirer les styles indésirables
            strong: {
              color: theme("colors.gray.900"),
              fontWeight: "700",
            },
            // Retirer les guillemets des blockquotes
            "blockquote p:first-of-type::before": {
              content: "none",
            },
            "blockquote p:last-of-type::after": {
              content: "none",
            },
          },
        },
      }),
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
