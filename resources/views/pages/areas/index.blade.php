<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Areas') }}
        </h2>
        <a href="{{ route('areas.create') }}">
            <x-primary-button>
                {{ __('Assign Area') }}
            </x-primary-button>
        </a>

    </x-slot>

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
            @livewire('areas.list-area')

        </div>
    </div>
</x-app-layout>
