<div class="mx-2 my-2 w-full overflow-x-auto">
    <table {{ $attributes->merge(['class' => 'w-full']) }}>
        {{$slot}}
    </table>
</div>
