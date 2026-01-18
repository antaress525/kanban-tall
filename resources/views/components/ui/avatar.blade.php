@props(['avatar' => null, 'name' => 'Unkown'])

<div class="w-8 h-8 rounded-full flex items-center justify-center overflow-hidden" aria-label="{{ $name }}">
    <img src="{{ $avatar }}" alt="{{ $name }}" class="w-full h-full object-cover">
</div>