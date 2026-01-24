<div x-data="{
        show: false,
        type: '',
        message: 'Notification message',
        timeout: null,
        notify(event) {
            this.type = event.detail.type || 'info';
            this.message = event.detail.message || '';
            this.show = true;
            clearTimeout(this.timeout);
            this.timeout = setTimeout(() => this.show = false, 3000);
        }
    }"
    x-on:notify.window="notify($event)"
    x-show="show"
    x-transition
    x-loack
    class="fixed top-4 right-4 max-w-sm p-3 rounded-lg shadow-lg text-sm font-semibold"
    :class="{
        'bg-blue-400 text-white': type === 'info',
        'bg-green-400 text-white': type === 'success',
        'bg-yellow-400 text-black': type === 'warning',
        'bg-red-400 text-white': type === 'error',
    }">
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-x-2">
            <!-- Icon -->
            <template x-if="type === 'info'">
                <x-lucide-info class="size-4" />
            </template>
            <template x-if="type === 'success'">
                <x-lucide-check class="size-4" />
            </template>
            <template x-if="type === 'warning'">    
                <x-lucide-alert-triangle class="size-4" />
            </template>
            <template x-if="type === 'error'">    
                <x-lucide-x-circle class="size-4" />
            </template>

            <!-- Text -->
            <span x-text="message"></span>
        </div>
        <button @click="show = false" class="ml-4 text-white cursor-pointer">
            <x-lucide-x class="size-4" />
        </button>
    </div>
</div>