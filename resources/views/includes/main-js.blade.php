@livewireScripts

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/js/all.min.js" integrity="sha512-naukR7I+Nk6gp7p5TMA4ycgfxaZBJ7MO5iC3Fp6ySQyKFHOGfpkSZkYVWV5R7u7cfAicxanwYQ5D1e17EfJcMA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bignumber.js/9.1.1/bignumber.min.js" integrity="sha512-RDkOXE/q9gnuJ/SUl8bUwbYOOXO8orhgIucuMbm3Wk8dtA/cNaUnWYrDONUwWsR//QmuPEIKyyudaP0c+EmMOQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

@include('sweetalert::alert')

<x-livewire-alert::scripts />

@stack('scripts')

<script>
    $(document).ready(function() {
    $('input').each(function() {
        var text = $(this).val();
        var latinNumeral = new BigNumber(text).toFormat();
        $(this).val(latinNumeral);
    });
});
</script>
