<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>{{ $title ?? config('app.name') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:300,400,500,600,700,800,900" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        @livewireStyles
    </head>
    <body  class="w-screen h-screen flex max-lg:flex-col">
        <!-- Nav Mobile -->
        <nav class="flex items-center justify-between h-13 p-4 lg:hidden">
            <x-ui.nav-mobile />
            <x-ui.avatar :name="auth()->user()->name" :avatar="'https://ui-avatars.com/api/?name='.auth()->user()->name"/>
        </nav>


        <!-- Nav desktop -->
        <nav class="hidden lg:flex bg-neutral-50 w-(--aside-width) h-full p-3.5  flex-col">
            <!-- Logo -->
            <div class="flex items-center gap-x-2 h-13 mb-13">
                <x-ui.logo />
                <p class="font-light">Focus <span class="font-medium">Board</span></p>
            </div>

            <!-- Navigation -->
            <div class="space-y-2 flex-1">
                <x-ui.nav-item wire:navigate :href="route('board.index')" :active="request()->routeIs('board.*')">
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
                <x-ui.nav-item 
                    href="{{ route('setting.account') }}" 
                    :active="request()->routeIs('setting.*')"
                    wire:navigate
                >
                    <x-slot:icon>
                        <x-lucide-cog class="size-5"/>
                    </x-slot:icon>
                    Parametre
                </x-ui.nav-item>
                <x-ui.user :name="auth()->user()->name" :avatar="'https://ui-avatars.com/api/?name='.auth()->user()->name"/>
            </div>
        </nav>
        <main class="flex-1 h-full">{{ $slot }}</main>
        <x-ui.notify />
        <!-- Modal Manager -->
        <livewire:modals.modal-manager />
        @livewireScripts
    </body>
</html>
