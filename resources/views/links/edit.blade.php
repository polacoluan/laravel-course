<x-layout.app>
    <x-container>
        <x-card title="Update Link">
            <x-form :route="route('links.edit', $link)" put id="form">
                <x-input type="link" name="link" placeholder="Link" value="{{ $link->link }}" />
                <x-input type="text" name="name" placeholder="Name" value="{{ $link->name }}" />
            </x-form>
            <x-slot:actions>
                <x-a :href="route('dashboard')">Cancel!</x-a>
                <x-button form="form">Save</x-button>
            </x-slot:actions>
        </x-card>
    </x-container>
</x-layout.app>