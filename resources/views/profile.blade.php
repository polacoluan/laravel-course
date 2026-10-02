<x-layout.app>
    <x-container>
        <x-card title="Profile">
            <x-form :route="route('profile')" put id="form" enctype="multipart/form-data">
                <div class="flex gap-2 items-center">
                    <x-img src="/storage/{{ $user->photo }}" alt="Profile Picture" />
                    <x-file-input name="photo" />
                </div>
                <x-input type="text" name="name" placeholder="Name" value="{{ $user->name }}" />
                <x-textarea name="description" value="{{ $user->description }}" />
                <x-input type="text" name="handler" prefix="biolinks.com.br/" placeholder="handler"
                    value="{{ $user->handler }}" />
            </x-form>
            <x-slot:actions>
                <x-a :href="route('dashboard')">Cancel!</x-a>
                <x-button form="form">Save</x-button>
            </x-slot:actions>
        </x-card>
    </x-container>
</x-layout.app>
