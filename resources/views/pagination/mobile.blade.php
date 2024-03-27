@if ($paginator->hasPages())
    <nav
        role="navigation"
        aria-label="{{ __('Pagination Navigation') }}"
        class="flex items-center justify-between sm:hidden"
    >
        @if ($paginator->onFirstPage())
            <x-payu::pagination.disabled class="px-3 py-[.3rem] rounded-md">
                {!! __('pagination.previous') !!}
            </x-payu::pagination.disabled>
        @else
            <x-payu::pagination.active href="{{ $paginator->previousPageUrl() }}" class="px-3 py-[.3rem] rounded-md">
                {!! __('pagination.previous') !!}
            </x-payu::pagination.active>
        @endif

        @if ($paginator->hasMorePages())
            <x-payu::pagination.active href="{{ $paginator->nextPageUrl() }}" class="px-3 py-[.3rem] rounded-md">
                {!! __('pagination.next') !!}
            </x-payu::pagination.active>
        @else
            <x-payu::pagination.disabled class="px-3 py-[.3rem] rounded-md">
                {!! __('pagination.next') !!}
            </x-payu::pagination.disabled>
        @endif
    </nav>
@endif
