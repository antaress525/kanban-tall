<?php

use Livewire\Component;
use Livewire\Attributes\On;

new class extends Component
{
    public bool $open = false;
    public string $type = 'center';
    public string $size = 'md';
    public ?string $component = null;
    public array $props = [];

    protected array $allowedTypes = [
        'center',
        'drawer',
        'fullscreen',
    ];

    #[On('open-modal')]
    public function openModal(string|null $type, string|null $size, string|null $component, array $props = [])
    {
        $this->type = in_array($type ?? 'center', $this->allowedTypes) ? $type : 'center';
        $this->size = $size ?? 'md';
        $this->component = $component ?? null;
        $this->props = $props ?? [];
        $this->open = true;
    }

    #[On('close-modal')]
    public function closeModal()
    {
        $this->open = false;
        $this->component = null;
        $this->props = [];
    }
};
?>

<div
    x-data="{ 
        open: @entangle('open'),
    }"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="{{ $type === 'drawer' ? 'transform translate-x-full' : 'opacity-0 scale-95' }}"
    x-transition:enter-end="{{ $type === 'drawer' ? 'transform translate-x-0' : 'opacity-100 scale-100' }}"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="{{ $type === 'drawer' ? 'transform translate-x-0' : 'opacity-100 scale-100' }}"
    x-transition:leave-end="{{ $type === 'drawer' ? 'transform translate-x-full' : 'opacity-0 scale-95' }}"
    @keydown.escape.window="$wire.closeModal()"
    class="fixed inset-0 z-50 bg-black/15"

>

    @php
        $typeClasses = match ($type) {
            'center' => 'mx-auto mt-24 rounded-xl',
            'drawer' => 'fixed right-0 top-0 h-full w-[28rem]',
            'fullscreen' => 'fixed inset-0',
            default => 'mx-auto mt-24 rounded-xl',
        };

        $sizeClasses = match ($size) {
            'xs' => 'max-w-xs',
            'sm' => 'max-w-sm',
            'md' => 'max-w-md',
            'lg' => 'max-w-2xl',
            'xl' => 'max-w-4xl',
            default => 'max-w-md',
        };
    @endphp

    <div 
        @click.outside="$wire.closeModal()"
        class="bg-white shadow-xl transition-all duration-300 {{ $typeClasses }} {{ $type !== 'fullscreen' ? $sizeClasses : '' }}"
    >
        @if ($component)
            <livewire:dynamic-component
                :component="$component"
                :key="$component . md5(json_encode($props))"
                :props="$props"
            />
        @endif
    </div>
</div>