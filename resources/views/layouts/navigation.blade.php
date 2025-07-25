<nav id="header" x-data="{ open: false }"
    class="fixed z-50 w-full border-b border-gray-100 bg-white dark:border-gray-700 dark:bg-gray-800">
    <div id="nav-content" class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 justify-between">
            <div class="flex">
                <div class="flex shrink-0 items-center">
                    <a href="{{ route('home') }}">
                        <img class="ml-4 h-12 w-12" src="{{ asset('/img/logo.jpg') }}" alt="">
                    </a>
                </div>
                <div class="flex shrink-0 items-center">
                    <a class="ml-5 text-3xl dark:text-white" href="{{ route('home') }}">
                        Booking Hotel
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    @if (request()->is('dashboard*'))
                        {{-- TAMPILAN NAVIGASI KETIKA DI HALAMAN DASHBOARD --}}
                        @can('admin')
                            {{-- Navigasi untuk ADMIN di halaman Dashboard --}}
                            {{-- <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                                {{ __('Dashboard') }}
                            </x-nav-link> --}}
                            <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                                {{ __('Users') }}
                            </x-nav-link>
                            <x-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index*')">
                                {{ __('Rooms') }}
                            </x-nav-link>
                            {{-- Anda perlu membuat route 'aings.index' --}}
                            <x-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.index')">
                                {{ __('Booking User') }}
                            </x-nav-link>
                        @else
                            {{-- Navigasi untuk USER BIASA di halaman Dashboard --}}
                            {{-- Anda perlu membuat route 'dashboard.pesanan' --}}
                            <x-nav-link :href="route('dashboard.pesanan')" :active="request()->routeIs('dashboard.pesanan')">
                                {{ __('Pesanan Saya') }}
                            </x-nav-link>
                        @endcan
                    @else
                        {{-- TAMPILAN NAVIGASI KETIKA DI HALAMAN PUBLIK (HOME, DLL) --}}
                        <x-nav-link :href="route('home')" :active="request()->routeIs('home')">
                            {{ __('Home') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <div class="hidden sm:ml-6 sm:flex sm:items-center">
                @auth
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none dark:bg-gray-800 dark:text-gray-400 dark:hover:text-gray-300">
                                <div>{{ Auth::user()->name }}</div>
                                <div class="ml-1">
                                    <svg class="h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <x-dropdown-link :href="route('profile.edit')">
                                {{ __('Profile') }}
                            </x-dropdown-link>
                            {{-- Link dropdown ini konsisten di semua halaman --}}
                            @can('admin')
                                <x-dropdown-link :href="route('users.index')">
                                    {{ __('Dashboard') }}
                                </x-dropdown-link>
                            @else
                                <x-dropdown-link :href="route('dashboard.pesanan')">
                                    {{ __('Pesanan Saya') }}
                                </x-dropdown-link>
                            @endcan
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    <div class="hidden space-x-8 sm:-my-px sm:flex">
                        <x-nav-link :href="route('login')" :active="request()->routeIs('login')">
                            {{ __('Login') }}
                        </x-nav-link>
                    </div>
                    <div class="hidden space-x-8 sm:-my-px sm:ml-5 sm:flex">
                        <x-nav-link :href="route('register')" :active="request()->routeIs('register')">
                            {{ __('Register') }}
                        </x-nav-link>
                    </div>
                @endauth
            </div>

            <div class="-mr-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-700 dark:focus:text-gray-400">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="space-y-1 pb-3 pt-2">
            @if (request()->is('dashboard*'))
                {{-- TAMPILAN NAVIGASI KETIKA DI HALAMAN DASHBOARD --}}
                @can('admin')
                    {{-- Navigasi untuk ADMIN di halaman Dashboard --}}
                    {{-- <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-responsive-nav-link> --}}
                    <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.index')">
                        {{ __('Users') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('rooms.index')" :active="request()->routeIs('rooms.index*')">
                        {{ __('Rooms') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('admin.bookings.index')" :active="request()->routeIs('admin.bookings.index')">
                        {{ __('Booking User') }}
                    </x-responsive-nav-link>
                @else
                    {{-- Navigasi untuk USER BIASA di halaman Dashboard --}}
                    <x-responsive-nav-link :href="route('dashboard.pesanan')" :active="request()->routeIs('dashboard.pesanan')">
                        {{ __('Pesanan Saya') }}
                    </x-responsive-nav-link>
                @endcan
            @else
                {{-- TAMPILAN NAVIGASI KETIKA DI HALAMAN PUBLIK --}}
                <x-responsive-nav-link :href="route('home')" :active="request()->routeIs('home')">
                    {{ __('Home') }}
                </x-responsive-nav-link>
            @endif
        </div>
        <div class="border-t border-gray-200 pb-1 pt-4 dark:border-gray-600">
            @auth
                <div class="px-4">
                    <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="text-sm font-medium text-gray-500">{{ Auth::user()->email }}</div>
                </div>
                <div class="mt-3 space-y-1">
                    <x-responsive-nav-link :href="route('profile.edit')">
                        {{ __('Profile') }}
                    </x-responsive-nav-link>
                    @can('admin')
                        <x-responsive-nav-link :href="route('dashboard')">
                            {{ __('Dashboard') }}
                        </x-responsive-nav-link>
                    @else
                        <x-responsive-nav-link :href="route('dashboard.pesanan')">
                            {{ __('Pesanan Saya') }}
                        </x-responsive-nav-link>
                    @endcan
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-responsive-nav-link>
                    </form>
                </div>
            @else
                <div class="space-y-1 pb-3 pt-2">
                    <x-responsive-nav-link :href="route('login')" :active="request()->routeIs('login')">
                        {{ __('Login') }}
                    </x-responsive-nav-link>
                    <x-responsive-nav-link :href="route('register')" :active="request()->routeIs('register')">
                        {{ __('Register') }}
                    </x-responsive-nav-link>
                </div>
            @endauth
        </div>
    </div>
</nav>
