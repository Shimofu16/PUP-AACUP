@extends('frontend.layouts.master')

@section('contents')
    @if ($article)
        <div class="relative w-full">
            <div class="relative">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->name }}"
                    class="h-[600px] w-full object-fill shadow-lg">
                <div class="absolute inset-0 bg-gray-600 opacity-50"></div>
            </div>
            <div class="absolute bottom-5 left-5 rounded-tl-lg p-2 text-white">
                <h1 class="text-3xl font-bold md:text-4xl lg:text-5xl">{{ $article->area->name }}:{{ $article->name }}</h1>
            </div>
        </div>
        <section class="container mx-auto mt-6 mb-10 px-2 sm:px-6">
            <div>
                <h1 class="border-b-3 border-maroon-700 pb-2 text-3xl font-bold text-gray-800">Description</h1>
                <p class="mt-2 text-base/8 text-left font-light text-gray-600 break-words">
                    {!! str($article->description)->sanitizeHtml() !!}
                </p>
            </div>
            <div class="mt-10 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="col-span-1">
                    <h2 class="text-xl font-bold text-gray-800">Areas</h2>
                    <div id="accordion-collapse" data-accordion="collapse" class="mt-4 pe-3">
                        @foreach ($areas as $area)
                            <h2 id="accordion-heading-{{ $area->id }}">
                                <button type="button"
                                    class="flex items-center justify-between w-full p-4 font-medium text-left text-gray-800 border border-gray-200 rounded-lg focus:ring-4 focus:ring-maroon-800 hover:text-white focus:text-white hover:bg-maroon-800 focus:bg-maroon-800"
                                    data-accordion-target="#accordion-body-{{ $area->id }}" aria-expanded="false"
                                    aria-controls="accordion-body-{{ $area->id }}">
                                    <span class="text-gray-800">{{ $area->name }}</span>
                                    <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                            stroke-width="2" d="M9 5 5 1 1 5"></path>
                                    </svg>
                                </button>
                            </h2>
                            <div id="accordion-body-{{ $area->id }}" class="hidden"
                                aria-labelledby="accordion-heading-{{ $area->id }}">
                                <ul class="p-4 space-y-2 border border-gray-200 rounded-lg">
                                    @foreach ($area->parameters()->orderBy('name', 'asc')->get() as $parameter)
                                        <li>
                                            <a href="{{ route('area.show', ['program_code' => $program_code, 'area' => $area->name, 'parameter' => $parameter->name]) }}"
                                                class="text-maroon-700 hover:underline">
                                                {{ $parameter->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-span-1 md:col-span-2">
                    <style>
                        .pdfobject-container {
                            height: 600px;
                            border: 1px solid #ccc;
                        }

                        @media (min-width: 768px) {
                            .pdfobject-container {
                                height: 800px;
                            }
                        }
                    </style>
                    <div id="pdf" class="mt-4 h-[400px] md:h-[600px]"></div>
                </div>
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


@endsection
