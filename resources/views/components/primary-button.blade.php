<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex justify-center items-center px-5 py-2.5 bg-gradient-to-r from-brand to-brand-dark hover:from-brand-dark hover:to-brand text-white font-bold rounded-full shadow-md hover:shadow-lg hover:shadow-brand/30 transition-all duration-300 transform hover:-translate-y-0.5 text-sm uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2']) }}>
    {{ $slot }}
</button>
