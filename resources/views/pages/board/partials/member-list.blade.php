<div x-data="{
        show: false,
        toggle() {
            this.show = !this.show
        },
        close() {
            this.show = false
        }
    }"
    @keyup.Escape.window="close()"
    class="relative"
>
    <button @click="toggle()" class="cursor-pointer">
        <x-ui.avatar-group>
            @foreach ($board->members()->limit(3)->get() as $member)
                <x-ui.avatar :avatar="$member->getAvatarUrl()" :name="$member->name" rounded="lg" class="border-2 border-white" />
            @endforeach
            @if ($board->members()->count() > 3)
                <x-ui.avatar-group-count count="{{ $board->members()->count() - 3 }}" />
            @endif
        </x-ui.avatar-group>
    </button>

    <div x-show="show" x-transition @click.outside="close()" class="absolute right-0 flex flex-col z-50 bg-white border border-neutral-200 rounded-md shadow-lg mt-2 min-w-64 max-h-94">
        <div class="p-2 text-sm text-neutral-500">
            Membres <span class="ml-2 px-2 py-0.5 rounded-full bg-amber-400 text-black text-xs">{{ $board->members->count() }}</span>
        </div>
        <div class="h-full overflow-y-auto flex-1">
            @foreach ($board->members as $member)
                <div class="flex items-center gap-x-2 p-2 cursor-pointer hover:bg-neutral-100">
                    <x-ui.avatar :avatar="$member->getAvatarUrl()" :name="$member->name" rounded="lg" />
                    <span class="text-sm text-neutral-700 font-medium">{{ $member->name }}</span>
                </div>
            @endforeach
        </div>
    </div>
</div>