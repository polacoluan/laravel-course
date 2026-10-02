<x-layout.app>
    <x-container>
        <x-card title="Login">
            <x-form :route="route('signin')" post id="login-form">
                <x-input type="email" name="email" placeholder="Email" />
                <x-input type="password" name="password" placeholder="Password" />
            </x-form>
            <x-slot:actions>
                <x-a :href="route('register')">Create an account!</x-a>
                <x-button form="login-form">Login</x-button>
            </x-slot:actions>
        </x-card>
    </x-container>
</x-layout.app>