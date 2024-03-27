@if ($paginator->hasPages())
    <div>
        @include('payu::pagination.mobile')
        @include('payu::pagination.desktop')
    </div>
@endif
