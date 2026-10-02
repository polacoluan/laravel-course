<x-layout.app>
    <x-container>
        <div class="absolute top-10 left-10 flex flex-col gap-4">
            <x-button :href="route('profile')" ghost>Update Profile</x-button>
            <x-button :href="route('links.create')" ghost>Create a new link</x-button>
            <x-form :route="route('logout')" post>
                <x-button ghost>Logout</x-button>
            </x-form>
        </div>
        <div class="text-center space-y-2 w-2/3">
            <x-img src="/storage/{{ $user->photo }}" alt="Profile Picture" />
            <div class="font-bold text-2xl tracking-wider">{{ $user->name }}</div>
            <div class="text-sm opacity-80">{{ $user->description }}</div>

            <ul class="space-y-2">
                @foreach ($links as $link)
                    <li class="flex items-center justify-center gap-2">
                        @unless ($loop->first)
                            <x-form :route="route('links.up', $link)" patch>
                                <x-button ghost>⮝</x-button>
                            </x-form>
                        @else
                            <x-button disabled>⮝</x-button>
                        @endunless
                        @unless ($loop->last)
                            <x-form :route="route('links.down', $link)" patch>
                                <x-button ghost>⮟</x-button>
                            </x-form>
                        @else
                            <x-button disabled>⮟</x-button>
                        @endunless

                        <x-button href="{{ route('links.edit', $link) }}" block outline info>{{ $link->name }}</x-button>

                        <x-form :route="route('links.destroy', $link)" delete onsubmit="return confirm('Are you sure?')">
                            <x-button ghost>🗑</x-button>
                        </x-form>
                    </li>
                @endforeach
            </ul>
        </div>
    </x-container>
</x-layout.app>
