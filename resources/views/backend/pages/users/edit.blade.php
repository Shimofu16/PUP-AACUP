
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit user') }}
        </h2>
        <a href="{{ route('backend.users.index') }}">
            <x-primary-button>
                {{ __('Back to list') }}
            </x-primary-button>
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg sm:p-6 lg:p-8">
            @livewire('users.edit-user', ['user' => $user])
        </div>
    </div>
</x-app-layout>
