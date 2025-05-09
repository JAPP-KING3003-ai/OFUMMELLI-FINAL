<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-light-text dark:text-dark-text leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-light-background dark:bg-dark-background">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-light-card dark:bg-dark-card overflow-hidden shadow-sm sm:rounded-lg border border-light-border dark:border-dark-border">
                <div class="p-6 text-light-text dark:text-dark-text">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>