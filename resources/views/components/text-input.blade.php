@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-gray-200 bg-white/50 focus:bg-white focus:border-brand focus:ring-brand rounded-lg shadow-sm transition duration-150']) }}>
