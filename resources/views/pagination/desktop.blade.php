@if ($paginator->hasPages())
    <nav class="hidden sm:flex-1 sm:flex items-center justify-center">
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
                    <x-payu::pagination.disabled class="px-2 py-1 border border-transparent">
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
