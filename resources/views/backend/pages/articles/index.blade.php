<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Articles') }}
        </h2>
        @if (request()->routeIs(['backend.articles.index']))
            <a href="{{ route('backend.articles.create') }}">
                <x-primary-button>
                    {{ __('Create Article') }}
                </x-primary-button>
            </a>
        @endif
    </x-slot>

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">

            <livewire:articles.list-article :status="$status">

        </div>
    </div>
</x-app-layout>
