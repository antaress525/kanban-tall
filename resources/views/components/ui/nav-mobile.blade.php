<div
    x-data="{ open: false }"
    x-effect="document.body.classList.toggle('overflow-hidden', open)"
    class="relative"
    {{ $attributes }}
>
    <!-- Toggle Button -->
    <button
        @click="open = !open"
        :aria-expanded="open.toString()"
        aria-controls="mobile-nav-menu"
        class="lg:hidden inline-flex items-center justify-center p-2 rounded-md hover:bg-neutral-100 focus-visible:ring-1 focus-visible:ring-violet-500"
    >
        <x-lucide-menu x-show="!open" class="size-6"/>
        <x-lucide-x x-show="open" class="size-6"/>
    </button>

    <!-- Overlay -->
    <div
        x-cloak
        x-show="open"
        x-transition:enter="transition-opacity duration-300 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity duration-200 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="open = false"
        class="fixed inset-0 z-40 bg-black/15 backdrop-blur-sm"
    ></div>

    <!-- Menu -->
    <template x-teleport="body">
        <nav
            x-cloak
            x-show="open"
            @keydown.escape.window="open = false"
            x-transition:enter="transition duration-300 ease-out"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition duration-200 ease-in"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            id="mobile-nav-menu"
            role="dialog"
            aria-modal="true"
            class="p-3.5 flex flex-col lg:hidden fixed inset-y-0 left-0 z-50 w-(--aside-width) max-w-sm bg-white text-neutral-900 shadow-xl transform"
        >
            <!-- Logo -->
            <div class="flex items-center gap-x-2 h-13 mb-13">
                <x-ui.logo />
                <p class="font-light">Focus <span class="font-medium">Board</span></p>
            </div>

            <!-- Navigation -->
            <div class="space-y-2 flex-1">
                <x-ui.nav-item href="/dashboard" :active="true">
                    <x-slot:icon>
                        <x-lucide-folder class="size-5"/>
                    </x-slot:icon>
                    Projet
                </x-ui.nav-item>
                <x-ui.nav-item href="/dashboard">
                    <x-slot:icon>
                        <x-lucide-bell class="size-5"/>
                    </x-slot:icon>
                    Notifications
                </x-ui.nav-item>
            </div>
            <!-- Footer -->
            <div class="space-y-2">
                <x-ui.nav-item href="#">
                    <x-slot:icon>
                        <x-lucide-cog class="size-5"/>
                    </x-slot:icon>
                    Parametre
                </x-ui.nav-item>
            </div>
        </nav>
    </template>
</div>