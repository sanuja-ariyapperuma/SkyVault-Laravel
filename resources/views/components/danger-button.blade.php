@props(['disabled' => false])

<button {{ $attributes->merge([
    'type' => 'submit',
    'disabled' => $disabled,
    'class' => 'btn-base gradient-danger text-white shadow-colored-danger hover:shadow-lg transform hover:-translate-y-0.5 focus:ring-danger-500 ' . ($disabled ? 'opacity-50 cursor-not-allowed' : '')
]) }}>
    {{ $slot }}
</button>
