@props(['socialMedia'])
<div>
    @foreach (json_decode($socialMedia) as $index => $socialMedia)
        <a class="inline-flex items-center text-base md:text-lg text-rendBrick-500 hover:text-rendBrick-600 font-semibold"
            href="{{ $socialMedia->value }}">
            @if ($socialMedia->name === 'Facebook')
                <i class="fab fa-facebook-square mr-3"></i>
            @elseif ($socialMedia->name === 'Instagram')
                <i class="fab fa-instagram-square mr-3"></i>
            @elseif ($socialMedia->name === 'Twitter')
                <i class="fab fa-twitter-square mr-3"></i>
            @elseif ($socialMedia->name === 'LinkedIn')
                <i class="fab fa-linkedin mr-3"></i>
            @elseif ($socialMedia->name === 'YouTube')
                <i class="fab fa-youtube-square mr-3"></i>
            @elseif ($socialMedia->name === 'Pinterest')
                <i class="fab fa-pinterest-square mr-3"></i>
            @elseif ($socialMedia->name === 'Snapchat')
                <i class="fab fa-snapchat-square mr-3"></i>
            @elseif ($socialMedia->name === 'TikTok')
                <i class="fab fa-tiktok mr-3"></i>
            @elseif ($socialMedia->name === 'WhatsApp')
                <i class="fab fa-whatsapp-square mr-3"></i>
            @else
                <i class="fas fa-globe mr-3"></i>
            @endif
            <span class="mr-3">{{ $socialMedia->name }}</span>
        </a>
    @endforeach
</div>
