/**
 * Lightweight Color Utility
 * Converts HEX to RGB and generates a shade scale 50-950
 */

// Convert HEX to RGB
export function hexToRgb(hex) {
    hex = hex.replace(/^#/, '');
    if (hex.length === 3) {
        hex = hex.split('').map(c => c + c).join('');
    }
    const r = parseInt(hex.substring(0, 2), 16);
    const g = parseInt(hex.substring(2, 4), 16);
    const b = parseInt(hex.substring(4, 6), 16);
    return { r, g, b };
}

// Convert RGB to HSL
export function rgbToHsl(r, g, b) {
    r /= 255;
    g /= 255;
    b /= 255;
    
    const max = Math.max(r, g, b);
    const min = Math.min(r, g, b);
    let h, s, l = (max + min) / 2;
    
    if (max === min) {
        h = s = 0; 
    } else {
        const d = max - min;
        s = l > 0.5 ? d / (2 - max - min) : d / (max + min);
        switch (max) {
            case r: h = (g - b) / d + (g < b ? 6 : 0); break;
            case g: h = (b - r) / d + 2; break;
            case b: h = (r - g) / d + 4; break;
        }
        h /= 6;
    }
    
    return { h: h * 360, s: s * 100, l: l * 100 };
}

// Convert HSL to RGB
export function hslToRgb(h, s, l) {
    h /= 360;
    s /= 100;
    l /= 100;
    
    let r, g, b;
    
    if (s === 0) {
        r = g = b = l; 
    } else {
        const hue2rgb = (p, q, t) => {
            if (t < 0) t += 1;
            if (t > 1) t -= 1;
            if (t < 1/6) return p + (q - p) * 6 * t;
            if (t < 1/2) return q;
            if (t < 2/3) return p + (q - p) * (2/3 - t) * 6;
            return p;
        };
        
        const q = l < 0.5 ? l * (1 + s) : l + s - l * s;
        const p = 2 * l - q;
        
        r = hue2rgb(p, q, h + 1/3);
        g = hue2rgb(p, q, h);
        b = hue2rgb(p, q, h - 1/3);
    }
    
    return {
        r: Math.round(r * 255),
        g: Math.round(g * 255),
        b: Math.round(b * 255)
    };
}

// Convert RGB to HEX
export function rgbToHex(r, g, b) {
    return "#" + [r, g, b].map(x => {
        const hex = x.toString(16);
        return hex.length === 1 ? '0' + hex : hex;
    }).join('');
}

// Generate palette using lightness scaling
export function generatePalette(baseHex) {
    const rgb = hexToRgb(baseHex);
    const hsl = rgbToHsl(rgb.r, rgb.g, rgb.b);
    
    const baseL = hsl.l;
    const lerp = (start, end, t) => start + (end - start) * t;
    
    const palette = {};
    
    // 50 to 400 (lighter)
    palette[50] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(96, baseL, 0))));
    palette[100] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(96, baseL, 0.2))));
    palette[200] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(96, baseL, 0.4))));
    palette[300] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(96, baseL, 0.6))));
    palette[400] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(96, baseL, 0.8))));
    
    // 500 is base
    palette[500] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, baseL)));
    
    // 600 to 950 (darker)
    palette[600] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(baseL, 10, 0.2))));
    palette[700] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(baseL, 10, 0.4))));
    palette[800] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(baseL, 10, 0.6))));
    palette[900] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(baseL, 10, 0.8))));
    palette[950] = rgbToHex(...Object.values(hslToRgb(hsl.h, hsl.s, lerp(baseL, 10, 0.9))));
    
    return palette;
}

// Register global function
if (typeof window !== 'undefined') {
    window.updateThemePalette = function(hexColor) {
        if (!hexColor) return;
        try {
            const palette = generatePalette(hexColor);
            const root = document.documentElement;
            
            Object.entries(palette).forEach(([shade, hex]) => {
                root.style.setProperty(`--color-primary-${shade}`, hex);
            });
        } catch (e) {
            console.error('Invalid color format provided to updateThemePalette', e);
        }
    };
}

// Auto-initialize based on CSS variable set in blade
if (typeof document !== 'undefined') {
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.documentElement;
        const primaryColor = getComputedStyle(root).getPropertyValue('--color-primary-500').trim();
        if (primaryColor && primaryColor.startsWith('#')) {
            window.updateThemePalette(primaryColor);
        }
    });
}
