<span
    aria-disabled="true"
    aria-label="{{ __('pagination.previous') }}"
>
    <span
        aria-hidden="true"
        {{ $attributes->merge(['class' => 'relative inline-flex transition ease-in-out duration-150 bg-transparent text-slate-600 select-none text-medium'])}}
    >
        {{ $slot }}
    </span>
</span>
