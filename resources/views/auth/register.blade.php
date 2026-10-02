<x-layout.app>
    <x-container>
        <x-card title="Register">
            <x-form :route="route('register')" post id="register-form">
                <x-input type="text" name="name" placeholder="Name" />
                <x-input type="email" name="email" placeholder="Email" />
                <x-input type="email" name="email_confirmation" placeholder="Email confirmation" />
                <x-input type="password" name="password" placeholder="Password" />
            </x-form>
            <x-slot:actions>
                <x-a :href="route('login')">Already have an account!</x-a>
                <x-button form="register-form">Register</x-button>
            </x-slot:actions>
        </x-card>
    </x-container>
</x-layout.app>