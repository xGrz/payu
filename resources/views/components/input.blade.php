@props([
    'type' => 'text',
    'name' => '',
    'value' => '',
    'step' => 1,
    'min' => null,
    'max' => null,
    'label' => '&nbsp',
    ])

<?php
$hasError = $errors->has($name);
$isNumeric = $type == 'number';
?>

<label class="inline-block w-full text-gray-700 font-bold mb-2 mt-1">
    @if ($label)
        <small class="@if($hasError) text-red-600 @else text-gray-500 @endif">{{$label}}</small>
    @endif
    <input
        {{ $attributes->class([
            'w-full border rounded-md py-2 px-2 shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-600',
            'border-gray-300' => !$hasError,
            'border-red-500' => $hasError,
            'text-right' => $isNumeric
        ])->merge([
            'type' => $type,
            'name' => $name,
            'value' => old($name, $value),
            'step' => $isNumeric ? ($step ?? null) : null,
            'min' => $isNumeric ? ($min ?? null) : null,
            'max' => $isNumeric ? ($max ?? null) : null,
        ])
        }}
    />
    @error($name)
    <small class="text-red-600 font-normal">{{ $message }}</small>
    @else <small>&nbsp;</small>
    @enderror

</label>
