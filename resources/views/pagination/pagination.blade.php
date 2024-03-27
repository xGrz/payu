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

    <nav class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
        <div class="flex items-center justify-between">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <x-payu::pagination.disabled class="p-1 mr-[.1rem] border border-slate-700 rounded-l-md">
                    <x-payu::icons.left-arrow />
                </x-payu::pagination.disabled>
            @else
                <x-payu::pagination.active href="{{ $paginator->previousPageUrl() }}" class="p-1 mr-[.1rem] border border-slate-700 rounded-l-md">
                    <x-payu::icons.left-arrow />
                </x-payu::pagination.active>
            @endif


            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <x-payu::pagination.disabled class="px-4 py-2 border border-slate-700">
                        {{ $element }}
                    </x-payu::pagination.disabled>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <x-payu::pagination.disabled class="px-2 py-1 mr-[.1rem] border border-slate-700 !text-slate-300 !bg-slate-500">
                                {{ $page }}
                            </x-payu::pagination.disabled>
                        @else
                            <x-payu::pagination.active href="{{$url}}" class="px-2 py-1 mr-[.1rem] border border-slate-700">
                                {{ $page }}
                            </x-payu::pagination.active>
                        @endif
                    @endforeach
                @endif
            @endforeach


            @if ($paginator->hasMorePages())
                <x-payu::pagination.active href="{{ $paginator->nextPageUrl() }}" class="p-1 mr-[.1rem] border border-slate-700 rounded-r-md">
                    <x-payu::icons.right-arrow />
                </x-payu::pagination.active>
            @else
                <x-payu::pagination.disabled class="p-1 mr-[.1rem] border border-slate-700 rounded-r-md">
                    <x-payu::icons.right-arrow />
                </x-payu::pagination.disabled>
            @endif


        </div>

    </nav>

@endif
