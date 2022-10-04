@props(['as' => 'div'])

<!-- The most horrobile way -->
<{{ $as }} x-data="perfectScroll" {{ $attributes->merge(['class' => 'relative max-h-full']) }} @mousemove="update">
    {{ $slot }}
</{{ $as }}>

<script>
    const perfectScroll = () => {
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
            },
            update
        }
    }
</script>