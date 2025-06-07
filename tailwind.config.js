/** @type {import('tailwindcss').Config} */
export default {
    content: [
      "./resources/**/*.blade.php",
      "./resources/**/*.js",
      "./resources/**/*.vue",
      "./node_modules/flowbite/**/*.js"
    ],
    darkMode: 'class',
    theme: {
      extend: {
        colors: {
            primary: "#E4763F",
            secondary: {
                "purple":"#7A24F9",
                "blue":"#17A1FA"
            },
            tertiary: {
                DEFAULT: "#F4F4F4",
                "100": "#FFFFFF",
                "200": "#C5C5C5",
                "300": "#B3B3B3",
                "400": "#C7C7C7",
                "500": "#BFBFBF",
                'title': "#8B8B8B",
                'title-line': "#E4E4E4",
                'table-line': "#EEEEEE"
            },
            success: "#00D24D",
            warning: {
                "100": "#DCCD00",
                "200": "#FFBF00"
            },
            danger: "#FF0000",
            btn: {
                "cancel": "#e5e7eb"
            }
        },
        fontFamily: {
            'body': [
            'Poppins',
            'ui-sans-serif',
            'system-ui',
            '-apple-system',
            'system-ui',
            'Segoe UI',
            'Roboto',
            'Helvetica Neue',
            'Arial',
            'Noto Sans',
            'sans-serif',
            'Apple Color Emoji',
            'Segoe UI Emoji',
            'Segoe UI Symbol',
            'Noto Color Emoji'
            ],
            'sans': [
            'Poppins',
            'ui-sans-serif',
            'system-ui',
            '-apple-system',
            'system-ui',
            'Segoe UI',
            'Roboto',
            'Helvetica Neue',
            'Arial',
            'Noto Sans',
            'sans-serif',
            'Apple Color Emoji',
            'Segoe UI Emoji',
            'Segoe UI Symbol',
            'Noto Color Emoji'
            ]
        },
        boxShadow: {
            'inner': [
                'inset 4px 6px 4px rgba(0,0,0,0.36)'
            ],
            'inner-pag': [
                'inset 0px 4px 4px 1px rgba(0,0,0,0.36)'
            ],
            'outer': [
                '-8px -6px 10px rgba(255,255,255,1.00)',
                '6px 4px 6px rgba(104,101,101,0.25)'
            ],
            'l-rb-outer': [
                '-8px 4px 10px rgba(255,255,255,1.00)',
                '6px 4px 6px rgba(104,101,101,0.25)'
            ],
            'outer-sidebar-primary': [
                '0px 10px 15px rgba(228, 118, 63,0.60)'
            ],
            'outer-sidebar-secondary': [
                '0px 10px 18px rgba(122, 36, 249,0.60)'
            ],
            'drop': [
                '0px 4px 4px 0px rgba(0,0,0,0.25)'
            ]
        }
    },
    plugins: [
        require("flowbite/plugin")
    ],
  }
}
