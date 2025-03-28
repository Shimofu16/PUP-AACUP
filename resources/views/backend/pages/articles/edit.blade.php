<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Article') }}
        </h2>
        <a href="{{ route('backend.articles.pending') }}">
            <x-primary-button>
                {{ __('Back to list') }}
            </x-primary-button>
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
            <div class="col-span-3 bg-white shadow-sm sm:rounded-lg sm:p-6 lg:p-8">
                @livewire('articles.edit-article', ['article' => $article])
            </div>
            <div class="col-span-1 ">
                <div class="bg-white shadow-sm sm:rounded-lg sm:p-2 lg:p-3">
                    @if ($article->status == 'declined')
                        <div class="flex flex-col">
                            <div class="flex flex-col gap-2">
                                <span class="font-semibold">Status:</span>
                                <span class="text-red-500">{{ $article->status }}</span>
                            </div>
                            <div class="flex flex-col gap-2">
                                <span class="font-semibold">Reason:</span>
                                <div class="break-words">{{ $article->reason }}</div>
                            </div>
                        </div>
                    @else
                        <div class="flex flex-col gap-2">
                            <span class="font-semibold">Status:</span>
                            <span>{{ $article->status }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
