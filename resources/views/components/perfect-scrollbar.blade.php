@props(['as' => 'div'])


<{{ $as }} x-data="perfectScroll" {{ $attributes->merge(['class' => 'relative max-h-full']) }} @mousemove="update">
    {{ $slot }}
</{{ $as }}>

<script>
    if (typeof perfectScroll === 'undefined') {
        window.perfectScroll = () => {
            let ps
            const update = () => {
                if (ps) {
                    ps.update()
                }
            }
            return {
                init(){
                    ps = new PerfectScrollbar(this.$el, {
                        wheelSpeed: 2,
                        wheelPropagation: false,
                        minScrollbarLength: 20
                    });
                    window.addEventListener('ps-reinitialize', () => {
                        if (ps) {
                            ps.update();
                        }
                    });
                },
                update
            }
        }
    }
</script>
