import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
const animate = require("tailwindcss-animate");
const plugin = require("tailwindcss/plugin");

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: ["class"],
    safelist: ["dark"],
    prefix: "",

    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
        "./pages/**/*.{ts,tsx,vue}",
        "./components/**/*.{ts,tsx,vue}",
        "./app/**/*.{ts,tsx,vue}",
        "./src/**/*.{ts,tsx,vue}",
    ],

    theme: {
    	container: {
    		center: true,
    		padding: {
    			DEFAULT: '.5rem',
    			lg: '.2rem',
    			sm: '1rem'
    		},
    		screens: {
    			'2xl': '1400px'
    		}
    	},
    	extend: {
    		fontFamily: {
    			sans: [
    				'Figtree',
                    ...defaultTheme.fontFamily.sans
                ],
    			roboto: [
    				'Roboto',
    				'sans-serif'
    			],
    			neuton: [
    				'Neuton',
    				'serif'
    			]
    		},
    		screens: {
    			smallscreen: '360px'
    		},
    		minWidth: {
    			'40vh': '40vh'
    		},
    		colors: {
    			marrom: {
    				'200': '#838180',
    				'300': '#534B47',
    				'400': '#5A463E',
    				'500': '#583B30',
    				'600': '#664031',
    				'700': '#61301D',
    				'800': '#451E0E',
    				'950': '#381304'
    			},
    			border: 'hsl(var(--border))',
    			input: 'hsl(var(--input))',
    			ring: 'hsl(var(--ring))',
    			background: 'hsl(var(--background))',
    			foreground: 'hsl(var(--foreground))',
    			primary: {
    				DEFAULT: 'hsl(var(--primary))',
    				foreground: 'hsl(var(--primary-foreground))'
    			},
    			secondary: {
    				DEFAULT: 'hsl(var(--secondary))',
    				foreground: 'hsl(var(--secondary-foreground))'
    			},
    			destructive: {
    				DEFAULT: 'hsl(var(--destructive))',
    				foreground: 'hsl(var(--destructive-foreground))'
    			},
    			muted: {
    				DEFAULT: 'hsl(var(--muted))',
    				foreground: 'hsl(var(--muted-foreground))'
    			},
    			accent: {
    				DEFAULT: 'hsl(var(--accent))',
    				foreground: 'hsl(var(--accent-foreground))'
    			},
    			popover: {
    				DEFAULT: 'hsl(var(--popover))',
    				foreground: 'hsl(var(--popover-foreground))'
    			},
    			card: {
    				DEFAULT: 'hsl(var(--card))',
    				foreground: 'hsl(var(--card-foreground))'
    			},
    			chart: {
    				'1': 'hsl(var(--chart-1))',
    				'2': 'hsl(var(--chart-2))',
    				'3': 'hsl(var(--chart-3))',
    				'4': 'hsl(var(--chart-4))',
    				'5': 'hsl(var(--chart-5))'
    			}
    		},
    		borderRadius: {
    			xl: 'calc(var(--radius) + 4px)',
    			lg: 'var(--radius)',
    			md: 'calc(var(--radius) - 2px)',
    			sm: 'calc(var(--radius) - 4px)'
    		},
    		keyframes: {
    			'accordion-down': {
    				from: {
    					height: 0
    				},
    				to: {
    					height: 'var(--radix-accordion-content-height)'
    				}
    			},
    			'accordion-up': {
    				from: {
    					height: 'var(--radix-accordion-content-height)'
    				},
    				to: {
    					height: 0
    				}
    			},
    			'collapsible-down': {
    				from: {
    					height: 0
    				},
    				to: {
    					height: 'var(--radix-collapsible-content-height)'
    				}
    			},
    			'collapsible-up': {
    				from: {
    					height: 'var(--radix-collapsible-content-height)'
    				},
    				to: {
    					height: 0
    				}
    			},
    			slideIn: {
    				'0%': {
    					opacity: 0,
    					transform: 'translateX(-20px)'
    				},
    				'100%': {
    					opacity: 1,
    					transform: 'translateX(0px)'
    				}
    			},
    			fadeIn: {
    				'0%': {
    					opacity: 0
    				},
    				'100%': {
    					opacity: 1
    				}
    			}
    		},
    		animation: {
    			'accordion-down': 'accordion-down 0.2s ease-out',
    			'accordion-up': 'accordion-up 0.2s ease-out',
    			'collapsible-down': 'collapsible-down 0.2s ease-in-out',
    			'collapsible-up': 'collapsible-up 0.2s ease-in-out',
    			'slide-in': 'slideIn .4s ease-in-out forwards',
    			'fade-in': 'fadeIn .4s ease-in-out forwards'
    		}
    	}
    },

    plugins: [
        forms,
        animate,
        plugin(function ({ addUtilities }) {
            function animationDelay() {
                const delays = {};
                for (let i = 0; i <= 12; i++) {
                    delays[`.animate-${i}`] = {
                        "animation-delay": `${i * 100}ms`,
                    };
                }
                return delays;
            }
            const newUtilities = animationDelay();
            addUtilities(newUtilities, ["responsive", "hover"]);
        }),
        require("tailwindcss-animate")
    ],
};
