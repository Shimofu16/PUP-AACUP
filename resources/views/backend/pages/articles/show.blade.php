<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ $article->program->name }}
        </h2>
        <a href="{{ $previousUrl }}">
            <x-primary-button>
                {{ __('Back') }} 
            </x-primary-button>
        </a>
    </x-slot>

    <div class="mx-auto max-w-7xl">
        <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg  p-5">
            <div class="relative w-full">
                <div class="relative">
                    <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->name }}"
                        class="h-[400px] w-full object-fill shadow-lg">
                    <div class="absolute inset-0 bg-gray-600 opacity-50"></div>
                </div>
                <div class="absolute bottom-5 left-5 rounded-tl-lg p-2 text-white">
                    <h1 class="text-3xl font-bold md:text-4xl lg:text-5xl">{{ $article->area->name }}:{{ $article->name }}</h1>
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
            

        </div>
    </div>
</x-app-layout>
