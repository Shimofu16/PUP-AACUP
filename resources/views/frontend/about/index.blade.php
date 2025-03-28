@extends('frontend.layouts.master')

@section('contents')
<section class="container mx-auto my-8 px-2 sm:px-6">
    <div class="grid grid-cols-4 gap-4">
        <div class="col-span-3 p-4">
            @switch($page)
                @case('PUP Calauan')
                    @include('frontend.about.partials.pup-calauan')
                @break
                @case('Vision, Mission and Function')
                    @include('frontend.about.partials.vision_mission_function')
                @break
                @case('History')
                    @include('frontend.about.partials.history')
                @break
                @default
            @endswitch
        </div>
        <div class="col-span-1 p-4">
            <h1 class="text-xl font-semibold text-maroon-700 border-b border-gray-400 w-fit">CONTENTS</h1>
            <ul class="mt-4 space-y-3 ">
                <li class="border-b border-gray-400 pb-2">
                    <a href="{{ route('about.index', ['page' => 'PUP Calauan']) }}"
                        class="block rounded-sm px-3 py-2 text-gray-900 hover:text-maroon-700 md:p-0 ">
                        PUP Calauan
                    </a>
                </li>
                <li class="border-b border-gray-400 pb-2">
                    <a href="{{ route('about.index', ['page' => 'Vision, Mission and Function']) }}"
                        class="block rounded-sm px-3 py-2 text-gray-900 hover:text-maroon-700 md:p-0 ">
                        Vision, Mission and Function
                    </a>
                </li>
                <li class="border-b border-gray-400 pb-2">
                    <a href="{{ route('about.index', ['page' => 'History']) }}"
                        class="block rounded-sm px-3 py-2 text-gray-900 hover:text-maroon-700 md:p-0 ">
                        History
                    </a>
                </li>
            </ul>
        </div>
    </div>
</section>
@endsection
