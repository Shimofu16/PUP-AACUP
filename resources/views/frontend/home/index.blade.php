@extends('frontend.layouts.master')

@section('contents')

    <!-- Hero Section (Campus Image) -->
    <div class="w-full">
        <img src="{{ asset('images/home-bg.jpg') }}" alt="PUP Calauan Campus"
            class="h-[400px] w-full object-fill shadow-lg">
    </div>

    <!-- Description Section -->
    <section class="container mx-auto mt-8 px-2 sm:px-6">
        @if ($programs->count() > 0)
            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($programs as $program)
                    <div class="max-w-md rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                        <svg class="mb-3 h-7 w-7 text-maroon-500" fill="currentColor" xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 640 512">
                            <path
                                d="M320 32c-8.1 0-16.1 1.4-23.7 4.1L15.8 137.4C6.3 140.9 0 149.9 0 160s6.3 19.1 15.8 22.6l57.9 20.9C57.3 229.3 48 259.8 48 291.9l0 28.1c0 28.4-10.8 57.7-22.3 80.8c-6.5 13-13.9 25.8-22.5 37.6C0 442.7-.9 448.3 .9 453.4s6 8.9 11.2 10.2l64 16c4.2 1.1 8.7 .3 12.4-2s6.3-6.1 7.1-10.4c8.6-42.8 4.3-81.2-2.1-108.7C90.3 344.3 86 329.8 80 316.5l0-24.6c0-30.2 10.2-58.7 27.9-81.5c12.9-15.5 29.6-28 49.2-35.7l157-61.7c8.2-3.2 17.5 .8 20.7 9s-.8 17.5-9 20.7l-157 61.7c-12.4 4.9-23.3 12.4-32.2 21.6l159.6 57.6c7.6 2.7 15.6 4.1 23.7 4.1s16.1-1.4 23.7-4.1L624.2 182.6c9.5-3.4 15.8-12.5 15.8-22.6s-6.3-19.1-15.8-22.6L343.7 36.1C336.1 33.4 328.1 32 320 32zM128 408c0 35.3 86 72 192 72s192-36.7 192-72L496.7 262.6 354.5 314c-11.1 4-22.8 6-34.5 6s-23.5-2-34.5-6L143.3 262.6 128 408z" />
                        </svg>
                        <a href="{{ route('programs.show', ['program_code' => $program->code]) }}">
                            <h5 class="mb-2 text-2xl font-semibold tracking-tight text-gray-900">
                                {{ $program->name }}</h5>
                        </a>
                        <p class="mb-3 font-normal text-gray-500"> {!! str(Str::limit($program->description, 200))->sanitizeHtml() !!}</p>
                        <a href="{{ route('programs.show',  ['program_code' => $program->code]) }}"
                            class="inline-flex items-center rounded-lg bg-maroon-700 px-2 sm:px-3 py-2 text-center text-sm font-medium text-white hover:bg-maroon-800 focus:outline-none focus:ring-4 focus:ring-maroon-300">
                            Read more
                            <svg class="ms-2 h-3.5 w-3.5 rtl:rotate-180" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M1 5h12m0 0L9 1m4 4L9 9" />
                            </svg>
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@endsection
