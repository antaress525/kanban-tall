<?php

use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\RenderLess;
use Illuminate\Support\Str;

new class extends Component
{
    public $notifications;
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
            ]);
        $this->unreadNotifications = auth()->user()
            ->unreadNotifications->count();
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
        }
    }"
    class="relative"
>
    <div @click="toggle" class="cursor-pointer h-8 w-full rounded-md flex gap-x-2 hover:bg-white hover:shadow-3xs border border-transparent hover:border-neutral-200 items-center text-sm px-2.5 font-medium text-neutral-600">
        <x-lucide-inbox class="w-5 h-5" />
        Notifications
        <span x-show="$wire.notifications.length > 0" class="ml-auto size-5 flex items-center justify-center rounded-md bg-red-500 text-white font-semibold" x-text="$wire.unreadNotifications">
        </span>
    </div>

    <template x-teleport="body">
        <div x-transition x-show="open" class="fixed top-3.5 bottom-3.5 left-62.5 bg-white shadow-lg border border-neutral-100 rounded-lg w-full max-w-xs">
            <!-- Header -->
            <div class="flex items-center p-3.5">
                <h3 class="text font-medium ">Notifications</h3>
                <span
                    x-show="$wire.notifications.length > 0"
                    class="size-4.5 ml-2 rounded bg-red-500 text-sm font-semibold text-white flex items-center justify-center"
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
                    wire:click="$set('unreadNotifications', 0)"
                    class="text-sm text-indigo-500 hover:text-indigo-700"
                >
                    Mark all as read
                </button>
            </div>

            <!-- Content -->
            <div>
                @foreach($notifications as $notification)
                    <div class="relative p-3.5 flex gap-2 items-start border-b border-neutral-100">
                        <!-- Unread indicator -->
                        <span wire:show="!{{ $notification['read_at'] }}" class="absolute size-2 rounded-full bg-red-400 top-4 right-4"></span>

                        <x-ui.avatar rounded="lg" class="shrink-0" :avatar="$notification['sender_avatar']" :name="$notification['sender_name']" />
                        <div>
                            <p class="text-sm font-medium text-neutral-900">
                                {{ Str::ucfirst($notification['sender_name']) }}
                            </p>
                            <span class="text-xs font-medium bg-amber-100 text-amber-500 px-1.5 py-0.5 rounded-full">{{ $notification['board_name'] }}</span>
                            <p class="text-neutral-400 text-sm">
                                La taches <span class="text-black">"{{ $notification['task_title'] }}"</span> vous a été assignée.
                            </p>
                            <div class="text-xs text-neutral-500 mt-2">
                                {{ $notification['created_at'] }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </template>
</div>