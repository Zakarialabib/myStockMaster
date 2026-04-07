@props(['type' => 'waves', 'color' => 'var(--theme-primary)'])

@if($type === 'waves')
    <svg width="850" height="145" viewBox="0 0 850 145" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M102.94 144.999C96.6306 145.048 0 142.432 0 135.175V0H850V47.7965C711.227 15.8466 566.86 11.4548 433.319 62.3735C377.8 83.5416 326.217 100.145 269.824 118.94C217.429 136.403 158.779 144.576 102.94 144.999Z" fill="url(#paint0_linear_310_4187)" />
        <defs>
            <linearGradient id="paint0_linear_310_4187" x1="572.75" y1="-78.9907" x2="572.75" y2="125.633" gradientUnits="userSpaceOnUse">
                <stop offset="0.1344" style="stop-color: var(--theme-secondary)" />
                <stop offset="1" style="stop-color: {{ $color }}" />
            </linearGradient>
        </defs>
    </svg>
@elseif($type === 'waves-bottom')
    <svg width="850" height="130" viewBox="0 0 850 130" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M747.06 0.000656128C753.369 -0.0479126 850 2.56776 850 9.825V130H0V97.2035C138.773 129.153 283.14 133.545 416.681 82.6265C472.2 61.4584 523.783 44.8554 580.176 26.0601C632.571 8.59695 691.221 0.423874 747.06 0.000656128Z" fill="url(#paint0_linear_310_4323)" />
        <defs>
            <linearGradient id="paint0_linear_310_4323" x1="277.25" y1="200.819" x2="277.25" y2="17.3636" gradientUnits="userSpaceOnUse">
                <stop offset="0.1344" style="stop-color: var(--theme-secondary)" />
                <stop offset="1" style="stop-color: {{ $color }}" />
            </linearGradient>
        </defs>
    </svg>
@elseif($type === 'triangle-top')
    <svg width="416" height="139" viewBox="0 0 416 139" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M366.015 139H0L61.7157 0H416L366.015 139Z" fill="url(#paint0_linear_307_104)" />
        <defs>
            <linearGradient id="paint0_linear_307_104" x1="280.311" y1="-75.7221" x2="280.311" y2="120.434" gradientUnits="userSpaceOnUse">
                <stop offset="0.1344" style="stop-color: var(--theme-secondary)" />
                <stop offset="1" style="stop-color: {{ $color }}" />
            </linearGradient>
        </defs>
    </svg>
@elseif($type === 'triangle-bottom')
    <svg width="498" height="405" viewBox="0 0 498 405" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M309.963 405H0V0H498L309.963 405Z" fill="url(#paint0_linear_307_1187)" />
        <defs>
            <linearGradient id="paint0_linear_307_1187" x1="335.564" y1="-220.629" x2="335.564" y2="350.906" gradientUnits="userSpaceOnUse">
                <stop offset="0.1344" style="stop-color: var(--theme-secondary)" />
                <stop offset="1" style="stop-color: {{ $color }}" />
            </linearGradient>
        </defs>
    </svg>
@elseif($type === 'dots')
    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="dotsPattern" width="20" height="20" patternUnits="userSpaceOnUse">
                <circle cx="2" cy="2" r="2" fill="{{ $color }}" opacity="0.2"/>
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#dotsPattern)" />
    </svg>
@elseif($type === 'grid')
    <svg width="100%" height="100%" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="gridPattern" width="20" height="20" patternUnits="userSpaceOnUse">
                <path d="M 20 0 L 0 0 0 20" fill="none" stroke="{{ $color }}" stroke-width="1" opacity="0.2"/>
            </pattern>
        </defs>
        <rect width="100%" height="100%" fill="url(#gridPattern)" />
    </svg>
@endif
