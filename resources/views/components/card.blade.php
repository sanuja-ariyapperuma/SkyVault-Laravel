@props(['title' => null, 'padding' => 'normal'])

<div {{ $attributes->merge(['class' => 'card-professional animate-fade-in']) }}>
    @if($title)
        <div class="bg-gradient-to-r from-gray-50 to-gray-100 px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
        </div>
    @endif
    
    <div class="{{ $padding === 'normal' ? 'p-6' : ($padding === 'tight' ? 'p-4' : 'p-8') }}">
        {{ $slot }}
    </div>
</div>
