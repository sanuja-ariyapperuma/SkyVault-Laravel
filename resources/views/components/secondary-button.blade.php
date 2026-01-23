@props(['disabled' => false])

<button {{ $attributes->merge([
    'type' => 'button',
    'disabled' => $disabled,
    'class' => 'btn-base bg-white text-gray-700 border-gray-300 shadow-soft hover:bg-gray-50 hover:shadow-medium focus:ring-primary-500 ' . ($disabled ? 'opacity-50 cursor-not-allowed' : '')
]) }}>
    {{ $slot }}
</button>
