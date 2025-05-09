<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-light-text dark:text-dark-text" />
            <x-text-input id="email" class="block mt-1 w-full border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text rounded-md" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" class="text-light-text dark:text-dark-text" />

            <x-text-input id="password" class="block mt-1 w-full border border-light-border dark:border-dark-border bg-light-card dark:bg-dark-card text-light-text dark:text-dark-text rounded-md"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center text-light-text dark:text-dark-text">
                <input id="remember_me" type="checkbox" class="rounded border-light-border dark:bg-dark-card dark:border-dark-border text-light-primary dark:text-dark-primary shadow-sm focus:ring-light-primary dark:focus:ring-dark-primary focus:ring-offset-light-background dark:focus:ring-offset-dark-background" name="remember">
                <span class="ms-2 text-sm text-light-text dark:text-dark-text">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-light-primary dark:text-dark-primary hover:text-light-hover dark:hover:text-dark-hover rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-light-primary dark:focus:ring-dark-primary" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3 bg-light-primary dark:bg-dark-primary text-white hover:bg-light-hover dark:hover:bg-dark-hover focus:ring-light-primary dark:focus:ring-dark-primary">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>