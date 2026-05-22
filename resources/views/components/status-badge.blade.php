@props(['status'])

@php
    $colors = [
        'active'   => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        'inactive' => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'sold'     => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        'rented'   => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'new'        => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300',
        'in_review'  => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        'contacted'  => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300',
        'closed'     => 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
    ];

    $value = $status instanceof \BackedEnum ? $status->value : $status;
    $label = $status instanceof \UnitEnum ? $status->label() : ucfirst(str_replace('_', ' ', $value));
    $color = $colors[$value] ?? 'bg-gray-100 text-gray-700';
@endphp

<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $color }}">
    {{ $label }}
</span>
