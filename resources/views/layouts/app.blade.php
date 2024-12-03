<!DOCTYPE html>
<html
    x-data="{
        darkMode: localStorage.getItem('darkMode') === 'true'
    }"
    x-bind:class="{ 'dark': darkMode }"
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Library Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- SweetAlert2 and Alpine.js -->
    @livewireStyles
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-sans antialiased dark:bg-gray-900 bg-gray-100">
    <div
        x-data="{
            sidebarOpen: false,
            darkMode: localStorage.getItem('darkMode') === 'true',
            toggleDarkMode() {
                this.darkMode = !this.darkMode;
                localStorage.setItem('darkMode', this.darkMode);
                document.documentElement.classList.toggle('dark', this.darkMode);
            }
        }"
        x-init="
            document.documentElement.classList.toggle('dark', darkMode);
            $watch('darkMode', (value) => {
                localStorage.setItem('darkMode', value);
                document.documentElement.classList.toggle('dark', value);
            })
        "
        class="flex h-screen overflow-hidden"
    >
        <!-- Rest of your existing layout remains the same -->
        <aside
            :class="{
                'translate-x-0 ease-out': sidebarOpen,
                '-translate-x-full ease-in': !sidebarOpen
            }"
            class="fixed z-40 inset-y-0 left-0 w-64 transition-transform duration-300 bg-white dark:bg-gray-800 shadow-lg lg:translate-x-0 lg:static lg:inset-0"
        >
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between p-4 border-b dark:border-gray-700">
                <h2 class="text-2xl font-bold text-gray-800 dark:text-white">
                    {{ config('app.name', 'Library') }}
                </h2>
                <button @click="sidebarOpen = false"
                    class="lg:hidden text-gray-500 hover:text-gray-700 focus:outline-none" aria-label="Close Sidebar">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Navigation Links -->
            <livewire:layout.navigation />

            <!-- Sidebar Footer -->
            <div class="absolute bottom-0 w-full p-4 border-t dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="User avatar"
                            class="w-10 h-10 rounded-full mr-3">
                        <div>
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ auth()->user()->role }}
                            </p>
                        </div>
                    </div>

                    <button @click="toggleDarkMode"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white"
                        title="Toggle Dark Mode" aria-label="Toggle Dark Mode">
                        <svg x-show="!darkMode" class="w-6 h-6" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m3.343-3.657l-1.414-1.414m12.728 12.728l1.414 1.414M12 8a4 4 0 100 8 4 4 0 000-8z">
                            </path>
                        </svg>
                        <svg x-show="darkMode" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    {{ now()->format('F j, Y H:i') }}
                </p>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white dark:bg-gray-800 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <button @click="sidebarOpen = true"
                            class="lg:hidden mr-4 text-gray-500 hover:text-gray-700 focus:outline-none"
                            aria-label="Open Sidebar">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                            @if (isset($header))
                                {{ $header }}
                            @else
                                Dashboard
                            @endif
                        </h1>
                    </div>


                    <!-- User Dropdown -->
                    <div x-data="{ userDropdown: false }" class="relative">
                        <button @click="userDropdown = !userDropdown"
                            class="flex items-center space-x-2 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-white">
                            <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="User"
                                class="w-8 h-8 rounded-full">
                            <span class="hidden md:block">{{ auth()->user()->name }}</span>
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </button>

                        <div x-show="userDropdown" @click.away="userDropdown = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 top-full mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg z-50 border dark:border-gray-700"
                            style="display: none;">
                            <a href="{{ route('profile.edit') }}"
                                class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                Profile
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    Log Out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <livewire:cart-modal />
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    @stack('scripts')
    <script src="{{ asset('vendor/livewire-alert/livewire-alert.js') }}"></script>
    <x-livewire-alert::flash />
</body>
</html>
