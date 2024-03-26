<div class="mx-2 my-2">
    <table {{ $attributes->merge(['class' => 'w-full']) }}>
        {{$slot}}
    </table>
</div>
