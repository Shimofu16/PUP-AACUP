@extends('frontend.layouts.master')

@section('contents')
    @if ($article)
        <div class="relative w-full">
            <div class="relative">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->name }}"
                    class="h-[400px] w-full object-fill shadow-lg">
                <div class="absolute inset-0 bg-gray-600 opacity-50"></div>
            </div>
            <div class="absolute bottom-5 left-5 rounded-tl-lg p-2 text-white">
                <h1 class="text-3xl font-bold md:text-4xl lg:text-5xl">{{ $article->area }}:{{ $article->name }}</h1>
            </div>
        </div>
        <section class="container mx-auto mt-6 px-2 sm:px-6">
            <div>
                <h1 class="border-b-3 border-maroon-700 pb-2 text-3xl font-bold text-gray-800">Description</h1>
                <p class="mt-2 text-lg text-gray-600">
                    {!! str($article->description)->sanitizeHtml() !!}
                </p>
            </div>
            <div class="mt-6">
                <style>
                    .pdfobject-container {
                        height: 800px;
                        border: 1px solid #ccc;
                    }
                </style>
                <div id="pdf" class="mt-4 h-[600px]"></div>
            </div>
            <script src="https://unpkg.com/pdfobject"></script>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const pdf = document.getElementById("pdf");
                    const options = {
                        pdfOpenParams: {
                            title: "{{ $article->name }}",
                            pdfOpenParams: {
                                view: 'Fit',
                                page: '1'
                            }
                        }
                    };
                    PDFObject.embed("{{ asset('storage/' . $article->document) }}", pdf);
                });
            </script>
        </section>
    @else
        <div class="flex items-center justify-center h-screen">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800">Article Not Found</h1>
                <p class="mt-2 text-lg text-gray-600">The article you are looking for does not exist.</p>
            </div>
        </div>
    @endif
    <section class="container mx-auto mt-6 px-2 sm:px-6">
        <div class="mt-6 mb-12">
            <div class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @foreach ($areas as $key => $area)
                    @if ($article)
                        <a href="{{ route('area.show', ['program_code' => $program_code, 'area' => $area]) }}"
                            class="text-center text-wrap py-2 rounded-lg border-2 border-maroon-700 hover:bg-maroon-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-maroon-300 {{ $article->area == $area ? 'bg-maroon-700 text-white' : 'bg-transparent text-maroon-700' }}">
                            {{ $area }}
                        </a>
                    @else
                        <a href="{{ route('area.show', ['program_code' => $program_code, 'area' => $area]) }}"
                            class="text-center text-wrap py-2 rounded-lg border-2 border-maroon-700 hover:bg-maroon-800 hover:text-white focus:outline-none focus:ring-4 focus:ring-maroon-300">
                            {{ $area }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>

@endsection
