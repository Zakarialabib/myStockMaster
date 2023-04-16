@props([
    'label' => '',
    'color' => 'blue',
    'class' => '',
    'shade' => 'faint',
    'color_weight' => [
        'faint' => 200,
        'dark' => 500,
    ],
    'text_color_weight' => [
        'faint' => 500,
        'dark' => 50,
    ],
])
<span
    class="bg-red-200 bg-yellow-200 bg-green-200 bg-pink-200 bg-cyan-200 bg-gray-200 bg-blue-200 bg-purple-200 bg-orange-200"></span>
<span
    class="bg-red-300 bg-yellow-300 bg-green-300 bg-pink-300 bg-cyan-300 bg-gray-300 bg-blue-300 bg-purple-300 bg-orange-300 bg-red-500 bg-yellow-500 bg-green-500 bg-pink-500 bg-gray-500 bg-cyan-500 bg-blue-500 bg-gray-500 bg-purple-500 bg-orange-500 text-red-500 text-yellow-500 text-green-500 bg-gray-500 text-pink-500 text-cyan-500 text-purple-500 text-orange-500 text-red-50 text-yellow-50 text-green-50 text-pink-50 text-cyan-50 text-purple-50 text-gray-50 text-blue-50 text-orange-50 bg-red-200 bg-yellow-200 bg-green-200 bg-pink-200 bg-cyan-200 bg-gray-200 bg-blue-200 bg-purple-200 bg-orange-200 border-red-500 border-yellow-500 border-green-500 border-pink-500 border-gray-500 border-cyan-500 border-blue-500 border-gray-500 border-purple-500 border-orange-500"></span>
<label style="zoom:95%" 
    class="text-xs uppercase px-[10px] py-[5px] tracking-widest whitespace-nowrap inline-block rounded-md bg-{{ $color }}-{{ $color_weight[$shade] }} text-{{ $color }}-{{ $text_color_weight[$shade] }} {{ $class }}">
    {{ $label }}
</label>