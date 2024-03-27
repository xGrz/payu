@if ($paginator->hasPages())
    <div class="flex justify-between flex-wrap">
        @include('payu::pagination.results')
        <div>
            @include('payu::pagination.mobile')
            @include('payu::pagination.pagination')
        </div>
    </div>
@endif
