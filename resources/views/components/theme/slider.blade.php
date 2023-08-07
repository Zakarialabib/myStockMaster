@props(['sliders'])
<section x-data="{
    active: 0,
    totalSlides: {{ count($sliders) }},
    slideInterval: 5000,
    autoplay: null,
    touchStartX: 0,
    touchEndX: 0,

    init() {
        this.startAutoplay();
    },

    startAutoplay() {
        this.autoplay = setInterval(() => {
            this.nextSlide();
        }, this.slideInterval);
    },

    stopAutoplay() {
        clearInterval(this.autoplay);
        this.autoplay = null;
    },

    nextSlide() {
        this.stopAutoplay();
        this.active = (this.active + 1) % this.totalSlides;
        this.startAutoplay();
    },

    prevSlide() {
        this.stopAutoplay();
        this.active = (this.active - 1 + this.totalSlides) % this.totalSlides;
        this.startAutoplay();
    },

    goToSlide(index) {
        this.stopAutoplay();
        this.active = index;
        this.startAutoplay();
    },

    handleTouchStart(e) {
        this.touchStartX = e.touches[0].clientX;
    },

    handleTouchEnd(e) {
        this.touchEndX = e.changedTouches[0].clientX;
        this.handleSwipeGesture();
    },

    handleSwipeGesture() {
        const threshold = 100;
        const deltaX = this.touchEndX - this.touchStartX;

        if (Math.abs(deltaX) > threshold) {
            if (deltaX > 0) {
                this.prevSlide();
            } else {
                this.nextSlide();
            }
        }
    }
}">
    <div class="relative overflow-hidden h-screen lg:min-h-[500px] bg-black">
        <div class="flex transition-all duration-500 relative" :style="{ left: -(active * 100) + '%' }">
            @foreach ($sliders as $index => $slider)
                <div class="relative h-screen min-w-full flex items-center lg:min-h-[500px]">
                    <picture>
                        <source media="(max-width: 800px)" srcset="{{ $slider->getFirstMediaUrl('local_files') }}">
                        <source media="(max-width: 1920px)" srcset="{{ $slider->getFirstMediaUrl('local_files') }}">
                        <img src="{{ $slider->getFirstMediaUrl('local_files') }}"
                            class="block absolute inset-0 w-full h-full object-cover object-center z-0">
                    </picture>
                    <div class="absolute bottom-0 w-full h-1/2 z-0 bg-gradient-to-t from-black to-transparent"></div>
                    <div
                        class="flex flex-col max-w-[1400px] items-center mx-auto my-auto text-center relative z-10 px-24 sm:px-18 lg:px-24">
                        <div class="my-4 font-bold text-xl text-white leading-[0.95] lg:text-5xl">
                            {{ $slider->title }}
                        </div>
                        <div
                            class="mb-4 text-3xl md:text-4xl font-heading font-bold text-white leading-normal lg:text-sm">
                            {{ $slider->subtitle }}
                        </div>
                        <p class="pb-10 text-md text-white leading-normal lg:text-sm lg:pt-0">
                            {!! $slider->description !!}
                        </p>
                        @if ($slider->link)
                            <a href="{{ $slider->link }}"
                                class="uppercase bg-[#fc1b1b] text-white h-12 inline-flex px-12 justify-center items-center text-sm font-oswald tracking-wider outline-none transition-colors hover:bg-[#b00303]">
                                {{ __('Read More') }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
        <div class="absolute bottom-10 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
            @foreach ($sliders as $index => $slider)
                <div class="w-14 h-1 bg-gray-300 cursor-pointer transition-colors hover:bg-[#b00303] bg-opacity-50"
                    :class="{ '!bg-[#fc1b1b]': active === {{ $index }} }"
                    @click="goToSlide({{ $index }})"></div>
            @endforeach
        </div>
        <div class="absolute top-1/2 -translate-y-1/2 right-10 text-white opacity-50 p-4 text-4xl z-10 cursor-pointer transition-colors hover:text-[#b00303]"
            @click="nextSlide">
            <i class="fa fa-angle-right"></i>
        </div>
        <div class="absolute top-1/2 -translate-y-1/2 left-10 text-white opacity-50 p-4 text-4xl z-10 cursor-pointer transition-colors hover:text-[#b00303]"
            @click="prevSlide">
            <i class="fa fa-angle-left"></i>
        </div>
    </div>
</section>
