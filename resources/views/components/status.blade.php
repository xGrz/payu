<?php

$commonStyles = 'inline-block px-1 leading-5';

$classes = match ($status->getColor()) {
    'gray' => 'text-gray-500 border-gray-400 bg-gray-50',
    'danger' => 'text-red-700 border-red-300 bg-red-50',
    'warning' => 'text-amber-700 border-amber-300 bg-amber-50',
    'success' => 'text-green-700 border-green-300 bg-green-50',
    'primary', 'info' => 'text-sky-700 border-sky-300 bg-sky-50',
    default => 'text-red-500'
};

if (!isset($withoutBorders)) $classes .= 'rounded border ';

?>

<span class="{{$commonStyles}} {{ $classes }} @if(isset($class)){{ $class }}@endif">
    {{ $status->getLabel() }}
</span>
