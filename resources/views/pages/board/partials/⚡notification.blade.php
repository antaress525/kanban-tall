<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\RenderLess;
use Illuminate\Support\Str;

new class extends Component
{
    public array $notifications;
    public int $unreadNotifications;

    public function mount() {
        $this->notifications = auth()->user()
            ->notifications()
            ->latest()
            ->get()
            ->map(fn ($notification) => [
                'id' => $notification->id,
                'task_title' => $notification->data['task_title'],
                'board_name' => Str::limit($notification->data['board_name'], 16),
                'sender_name' => $notification->data['sender_name'],
                'sender_avatar' => $notification->data['sender_avatar'],
                'created_at' => $notification->created_at->diffForHumans(),
                'read_at' => $notification->read_at,
            ])
            ->toArray();
        $this->unreadNotifications = auth()->user()
            ->unreadNotifications->count();
    }

    #[RenderLess]
    public function markAllAsRead() {
        auth()->user()->unreadNotifications->markAsRead();
    }
};
?>

<div
    x-data="{
        open: false,
        close() {
            this.open = false
        },
        toggle() {
            this.open = !this.open
        },
        markAllAsRead() {
                $wire.unreadNotifications = 0;
                $wire.notifications.forEach(notification => {
                    if (!notification.read_at) {
                        notification.read_at = new Date().toISOString();
                    }
                });
                $wire.markAllAsRead();
        },
    }"
    x-init="
        Echo.private('App.Models.User.{{ auth()->id() }}')
            .notification((notification) => {
                $wire.notifications.unshift({
                    id: notification.id,
                    task_title: notification.task_title,
                    board_name: notification.board_name,
                    sender_name: notification.sender_name,
                    sender_avatar: notification.sender_avatar,
                    created_at: 'Just now',
                    read_at: null,
                });
                $wire.unreadNotifications++;
            });
    "
    class="relative"
>
    <div @click="toggle" class="cursor-pointer h-8 w-full rounded-md flex gap-x-2 hover:bg-white hover:shadow-3xs border border-transparent hover:border-neutral-200 items-center text-sm px-2.5 font-medium text-neutral-600">
        <x-lucide-inbox class="w-5 h-5" />
        Notifications
        <span x-cloak x-show="$wire.unreadNotifications > 0" x-transition class="ml-auto size-5 flex items-center justify-center rounded-md bg-red-500 text-white font-semibold" x-text="$wire.unreadNotifications">
        </span>
    </div>

    <template x-teleport="body">
            <div @click.outside="close" x-cloak x-transition x-show="open" class="fixed hidden lg:flex top-3.5 bottom-3.5 left-62.5 flex-col bg-white shadow-lg border border-neutral-100 rounded-lg w-full max-w-xs">
            <!-- Header -->
            <div class="flex items-center p-3.5">
                <h3 class="text font-medium ">Notifications</h3>
                <span
                    wire:show="{{ count($notifications) }} > 0"
                    class="size-4.5 ml-2 rounded bg-neutral-100 text-sm font-semibold text-neutral-500 flex items-center justify-center"
                    x-text="$wire.notifications.length"
                ></span>
                <x-ui.icon-button
                    size="xs"
                    class="ml-auto"
                    @click="close"
                >
                    <x-lucide-x class="size-3.5 text-neutral-500"/>
                </x-ui.icon-button>
            </div>

            <!-- Actions -->
            <div class="bg-neutral-100 px-3.5 py-2">
                <button
                    @click="markAllAsRead"
                    class="text-sm text-indigo-500 hover:text-indigo-700"
                >
                    Marquer tout comme lu
                </button>
            </div>

            <!-- Content -->
            <div class="flex-1 overflow-y-auto" x-bind:class="$wire.notifications.length === 0 ? 'flex items-center justify-center' : ''"   >
                <!-- Not empty -->
                <template x-if="$wire.notifications.length > 0">
                    <template x-for="notification in $wire.notifications">
                        <div class="relative cursor-pointer p-3.5 flex gap-2 items-start border-b border-neutral-100">
                            <!-- Unread indicator -->
                            <span x-show="!notification.read_at" x-transition class="absolute size-2 rounded-full bg-red-400 top-4 right-4"></span>

                            <!-- Avatar -->
                            <div class="size-8 shrink-0 rounded-lg flex items-center justify-center overflow-hidden">
                                <img x-bind:src="notification.sender_avatar" x-bind:alt="notification.sender_name" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <p class="text-sm font-medium text-neutral-900">
                                    <span x-text="notification.sender_name"></span>
                                </p>
                                <p class="text-neutral-400 text-sm">
                                    La taches <span class="text-black" x-text="notification.task_title"></span> vous a été assignée.
                                </p>
                                <div class="flex items-center justify-between mt-1">
                                    <span class="text-xs text-neutral-500" x-text="notification.created_at"></span>
                                    <span class="text-xs font-medium bg-amber-100 text-amber-500 px-1.5 py-0.5 rounded-full" x-text="notification.board_name"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </template>

                <!-- Empty -->
                <template x-if="$wire.notifications.length === 0">
                    <div class="flex flex-col gap-2 justify-center items-center text-neutral-400">
                        <x-lucide-inbox class="size-6 block" />
                        <span class="text-sm">Aucune notification</span>
                    </div>
                </template>
            </div>
        </div>
    </template>

</div>