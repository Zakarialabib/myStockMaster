/**
 * Color Utility for Theme Generation
 * Matches the PHP generate_color_palette logic for consistency
 */

// Convert HEX to RGB
function hexToRgb(hex) {
    hex = hex.replace(/^#/, '');
    if (hex.length === 3) {
        hex = hex[0] + hex[0] + hex[1] + hex[1] + hex[2] + hex[2];
    }
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    return [r, g, b];
}

// Mix two colors based on weight
function mixColors(color1, color2, weight) {
    const w = weight / 100;
    const r = Math.round(color1[0] * w + color2[0] * (1 - w));
    const g = Math.round(color1[1] * w + color2[1] * (1 - w));
    const b = Math.round(color1[2] * w + color2[2] * (1 - w));

    return (
        '#' +
        [r, g, b]
            .map((x) => {
                const hex = x.toString(16);
                return hex.length === 1 ? '0' + hex : hex;
            })
            .join('')
    );
}

// Generate palette using the same logic as PHP generate_color_palette
export function generatePalette(baseHex) {
    const baseRgb = hexToRgb(baseHex);
    const white = [255, 255, 255];
    const black = [0, 0, 0];

    return {
        50: mixColors(baseRgb, white, 10),
        100: mixColors(baseRgb, white, 20),
        200: mixColors(baseRgb, white, 40),
        300: mixColors(baseRgb, white, 60),
        400: mixColors(baseRgb, white, 80),
        500: baseHex.startsWith('#') ? baseHex : '#' + baseHex,
        600: mixColors(baseRgb, black, 80),
        700: mixColors(baseRgb, black, 60),
        800: mixColors(baseRgb, black, 40),
        900: mixColors(baseRgb, black, 20),
        950: mixColors(baseRgb, black, 10),
    };
}

// Global function to update theme
if (typeof window !== 'undefined') {
    window.updateTheme = function (settings) {
        if (!settings) return;

        const root = document.documentElement;

        // Update Colors
        if (settings.primary_color) {
            try {
                const palette = generatePalette(settings.primary_color);
                Object.entries(palette).forEach(([shade, hex]) => {
                    root.style.setProperty(`--color-primary-${shade}`, hex);
                });
            } catch (e) {
                console.error('Theme: Error generating palette', e);
            }
        }

        // Update Fonts
        if (settings.font_family) {
            root.style.setProperty('--font-sans', settings.font_family);
            root.style.setProperty('--font-body', settings.font_family);
            document.body.style.fontFamily = settings.font_family;
        }
    };
}

// Initialize based on window.themeSettings
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        if (window.themeSettings) {
            window.updateTheme(window.themeSettings);
        }
    });

    // Support Livewire updates
    document.addEventListener('livewire:navigated', () => {
        if (window.themeSettings) {
            window.updateTheme(window.themeSettings);
        }
    });

    // Handle real-time theme updates from Livewire
    window.addEventListener('theme-updated', (event) => {
        if (event.detail && event.detail.settings) {
            window.updateTheme(event.detail.settings);
            // Also update global window.themeSettings to persist across navigation
            window.themeSettings = event.detail.settings;
        }
    });
}
