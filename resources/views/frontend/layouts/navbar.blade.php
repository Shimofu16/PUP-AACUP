<nav class="bg-white border-gray-200 shadow-sm sticky top-0 z-50">
    <div class="mx-auto flex max-w-screen-xl flex-wrap items-center justify-between p-4">
        <a href="#" class="flex items-center space-x-3 rtl:space-x-reverse">
            <x-application-logo class="h-8" />
            <span class="self-center whitespace-nowrap text-2xl font-semibold hidden sm:block">
                Polytechnic University of the Philippines
            </span>
        </a>

        <!-- Mobile Menu Button -->
        <button id="navbar-toggle" type="button"
            class="inline-flex h-10 w-10 items-center justify-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 md:hidden"
            aria-controls="navbar-dropdown" aria-expanded="false">
            <span class="sr-only">Open main menu</span>
            <svg class="h-5 w-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 17 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M1 1h15M1 7h15M1 13h15" />
            </svg>
        </button>

        <!-- Navbar Menu -->
        <div class="hidden w-full md:block md:w-auto" id="navbar-dropdown">
            <ul class="mt-4 flex flex-col rounded-lg border border-gray-100 p-4 font-medium md:mt-0 md:flex-row md:space-x-8 md:border-0 md:p-0 rtl:space-x-reverse">
                <li>
                    <a href="{{ route('home.index') }}"
                        class="block rounded-sm px-3 py-2 text-gray-900 md:p-0 {{ request()->routeIs('home.index') ? 'text-maroon-700' : 'hover:bg-maroon-100 md:border-0 md:hover:bg-transparent md:hover:text-maroon-700' }}">
                        Home
                    </a>
                </li>

                <!-- Dropdown -->
                <li class="relative group">
                    <button id="dropdown-toggle"
                        class="flex w-full items-center justify-between rounded-sm px-3 py-2 text-gray-900 md:w-auto md:border-0 md:p-0 md:hover:bg-transparent md:hover:text-maroon-700">
                        Program Under Survey
                    </button>

                    <div id="programsDropdown"
                        class="hidden md:absolute md:left-0 md:z-10 w-80 divide-y divide-gray-100 rounded-lg bg-white font-normal shadow-sm group-hover:block md:group-hover:block">
                        <ul class="py-2 text-sm text-gray-700">
                            @if (count($programs) > 0)
                                @foreach ($programs as $program)
                                    <li class="relative">
                                        <!-- Program Item with Clickable Toggle -->
                                        <button class="program-toggle w-full text-left px-4 py-2 hover:bg-maroon-700 hover:text-white flex justify-between items-center">
                                            {{ $program->name }}
                                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        <!-- Areas Dropdown -->
                                        <ul class="areas-dropdown hidden absolute left-0 top-full w-full bg-white shadow-lg rounded-lg z-20">
                                            @foreach ($areas as $key  => $area)
                                                <li>
                                                    <a href="{{ route('area.show', ['program_code' => $program->code, 'area' => $area]) }}"
                                                        class="block px-4 py-2 hover:bg-maroon-700 hover:text-white">
                                                        {{ $area }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                    <hr class="my-2 border-gray-200">
                                @endforeach
                            @else
                                <li>
                                    <a href="#" class="block px-4 py-2 hover:bg-maroon-700 hover:text-white">No program available</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </li>

                <li>
                    <a href="{{ route('about.index') }}"
                        class="block rounded-sm px-3 py-2 text-gray-900 md:p-0 {{ request()->routeIs('about.index') ? 'text-maroon-700' : 'hover:bg-maroon-100 md:border-0 md:hover:bg-transparent md:hover:text-maroon-700' }}">
                        About PUP Calauan
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<!-- JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const navbarToggle = document.getElementById("navbar-toggle");
    const navbarDropdown = document.getElementById("navbar-dropdown");
    const dropdownToggle = document.getElementById("dropdown-toggle");
    const programsDropdown = document.getElementById("programsDropdown");

    // Toggle mobile menu
    navbarToggle.addEventListener("click", function () {
        navbarDropdown.classList.toggle("hidden");
    });

    // Toggle dropdown inside mobile menu (only for mobile)
    dropdownToggle.addEventListener("click", function (event) {
        event.stopPropagation(); // Prevents click from closing the menu immediately
        programsDropdown.classList.toggle("hidden");
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", function (event) {
        if (!dropdownToggle.contains(event.target) && !programsDropdown.contains(event.target)) {
            programsDropdown.classList.add("hidden");
        }
    });

    // Toggle areas dropdown on program item click
    const programToggles = document.querySelectorAll(".program-toggle");
    programToggles.forEach((toggle) => {
        toggle.addEventListener("click", function (event) {
            event.stopPropagation(); // Prevent event from bubbling up
            const areasDropdown = toggle.nextElementSibling; // Get the associated areas dropdown
            areasDropdown.classList.toggle("hidden"); // Toggle visibility
        });
    });

    // Close areas dropdown when clicking outside
    document.addEventListener("click", function (event) {
        programToggles.forEach((toggle) => {
            const areasDropdown = toggle.nextElementSibling;
            if (!toggle.contains(event.target)) {
                areasDropdown.classList.add("hidden");
            }
        });
    });
});
</script>

