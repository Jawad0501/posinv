/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./vendor/mattlibera/livewire-flash/src/publish/livewire-flash.php",
        "./vendor/mattlibera/livewire-flash/src/views/livewire/*.blade.php",
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
    ],
    theme: {
        container: {
            center: true,
            padding: {
                DEFAULT: "1rem",
                sm: "2rem",
                lg: "4rem",
                xl: "3rem",
                "2xl": "4rem",
            },
        },
        screens: {
            sm: "576px",
            md: "768px",
            lg: "992px",
            xl: "1200px",
            "2xl": "1480px",
        },
        fontFamily: {
            nunito: ["Nunito", "sans-serif"],
            "open-sans": ['"Open Sans"', "sans-serif"],
        },
        colors: {
            transparent: "transparent",
            current: "currentColor",
            white: "#ffffff",
            light: "#f9fafa",
            black: "#090909",
            primary: {
                50: "#FEF4E1",
                100: "#FEE2B4",
                200: "#FDCF83",
                300: "#FDBC51",
                400: "#FDAD2B",
                500: "#FD9F0B",
                600: "#F99307",
                700: "#F38304",
                800: "#ED7402",
            },
            success: {
                50: "#E1FDE8",
                100: "#B7F7C6",
                200: "#7FF2A0",
                300: "#02ED74",
                400: "#00E64D",
                500: "#00DD2E",
                600: "#00CC21",
                700: "#00B80C",
                800: "#00A600",
            },
            warning: {
                50: "#FFE7E6",
                100: "#FFC8B9",
                200: "#FFA38D",
                300: "#FF7B5F",
                400: "#FF583B",
                500: "#FF2B18",
                600: "#FF2313",
                700: "#FB180D",
                800: "#ED0206",
            },
            info: {
                50: "#E3F3FF",
                100: "#BBE1FF",
                200: "#8ECFFF",
                300: "#5DBBFF",
                400: "#30ACFF",
                500: "#009DFF",
                600: "#008EFF",
                700: "#027BED",
                800: "#096ADB",
            },
            gray: {
                50: "#F5F5F5",
                100: "#E9E9E9",
                200: "#D9D9D9",
                300: "#C4C4C4",
                400: "#9D9D9D",
                500: "#7B7B7B",
                600: "#555555",
                700: "#434343",
                800: "#262626",
            },
        },
        extend: {
            fontSize: {
                md: ["18px", "20px"],
                lg: ["26px", "28px"],
                xl: ["30px", "32px"],
                "2xl": ["36px", "48px"],
                "3xl": ["44px", "48px"],
                "4xl": ["60px", "72px"],
            },
        },
    },
    plugins: [
        function ({ addComponents }) {
            addComponents({
                ".container": {
                    maxWidth: "100%",
                    // '@screen sm': {
                    //   maxWidth: '540px',
                    // },
                    // '@screen md': {
                    //   maxWidth: '720px',
                    // },
                    // '@screen lg': {
                    //   maxWidth: '960px',
                    // },
                    // '@screen xl': {
                    //   maxWidth: '1140px',
                    // },
                    // '@screen 2xl': {
                    //   maxWidth: '1920px',
                    // },
                },
            });
        },
    ],
};
