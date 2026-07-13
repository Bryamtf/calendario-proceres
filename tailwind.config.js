import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.js",
    ],

    theme: {
        extend: {
            colors: {
                ink: "#26282B",
                paper: "#FAF8F4",
                primary: {
                    DEFAULT: "#2B3A4A",
                    light: "#3D5066",
                    dark: "#1E2A36",
                },
                accent: "#C08A3E",
                sage: "#5B7A5D",
                brick: "#A64B3F",
                line: "#E4DFD3",
            },
            fontFamily: {
                display: ["Fraunces", ...defaultTheme.fontFamily.serif],
                sans: ["Inter", ...defaultTheme.fontFamily.sans],
                mono: ["IBM Plex Mono", ...defaultTheme.fontFamily.mono],
            },
        },
    },

    plugins: [forms],
};
