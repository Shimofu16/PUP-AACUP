@extends('frontend.layouts.master')

@section('contents')
    <div class="relative w-full">
        <div class="relative">
            <img src="{{ asset('storage/' . $program->image) }}" alt="{{ $program->name }}"
            class="h-[600px] w-full object-fill shadow-lg">
            <div class="absolute inset-0 bg-gray-600 opacity-50"></div>
        </div>
        <div class="absolute bottom-5 left-5 rounded-tl-lg p-2 text-white ">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold">{{ $program->name }}</h1>
        </div>
    </div>
    <section class="container mx-auto mt-6 px-2 sm:px-6">
        <div>
            <h1 class="border-b-3 border-maroon-700 pb-2 text-3xl font-bold text-gray-800">Description</h1>
            <p class="mt-2 text-lg text-gray-600">
                {!! str($program->description)->sanitizeHtml() !!}
            </p>
        </div>
        <div class="mt-6 mb-10">
            <h1 class="border-b-3 border-maroon-700 pb-2 text-3xl font-bold text-gray-800">Areas under survey</h1>
            <div class="mt-4 grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                @forelse ($program->articles()->where('status', 'accepted')->orderBy('area_id', 'asc')->get() as $article)
                    <div class="rounded-lg bg-white p-4 shadow-sm">
                        <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->name }}"
                            class="h-[200px] w-full rounded-lg object-fill shadow-lg">
                            <a href="{{ route('area.show', ['program_code' => $program->code, 'area' =>$article->area]) }}"
                                class="underline">
                                <h1 class="mb-3 mt-2 text-xl font-bold text-gray-800">{{ $article->area->name }}</h1>
                                <h1 class="mb-3 mt-2 text-xl text-gray-800">{{ $article->name }}</h1>

                               </a>

                    </div>
                    @empty
                    <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 text-center">
                        <h1 class="mb-3 mt-2 text-xl font-bold text-gray-800 rounded-lg bg-white p-4 shadow-sm text-center">No survey available</h1>
                    </div>
                @endforelse
            </div>
        </div>

    </section>
@endsection
