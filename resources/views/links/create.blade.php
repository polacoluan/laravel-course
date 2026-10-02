<x-layout.app>
    <x-container>
        <x-card title="Create Link">
            <x-form :route="route('links.store')" post id="form">
                <x-input type="link" name="link" placeholder="Link" />
                <x-input type="text" name="name" placeholder="Name" />
            </x-form>
            <x-slot:actions>
                <x-a :href="route('dashboard')">Cancel!</x-a>
                <x-button form="form">Save</x-button>
            </x-slot:actions>
        </x-card>
    </x-container>
</x-layout.app>