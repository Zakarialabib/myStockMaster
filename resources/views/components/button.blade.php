<button {{ $attributes->merge(['type' => 'submit', 'class' => 'bg-green-500 text-white focus:outline-none focus:ring-2 focus:ring-green-400 focus:ring-offset-2 focus:ring-offset-green-50 text-white font-semibold h-10 px-2 rounded-lg flex items-center justify-center sm:w-auto']) }}>
    {{ $slot }}
</button>
