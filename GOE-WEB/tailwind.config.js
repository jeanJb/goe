/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx}",
    "*.{js,ts,jsx,tsx,mdx}",
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: "#eff6ff",
          100: "#dbeafe",
          500: "#0059ff",
          600: "#1461b8",
          700: "#1d4ed8",
          900: "#1e3a8a",
        },
        sidebar: {
          light: "#fff",
          dark: "#000",
        },
        body: {
          light: "#f5f5f5",
          dark: "#18191a",
        },
      },
      fontFamily: {
        sans: ["Poppins", "sans-serif"],
      },
    },
  },
  plugins: [require("tailwindcss-animate")],
}
