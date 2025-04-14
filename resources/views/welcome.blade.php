<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>XPro Library - Digital Knowledge Hub</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body x-data="{
    darkMode: localStorage.getItem('darkMode') === 'true',
    activeTab: 'features',
    books: {{ json_encode($books) }},
    categories: {{ json_encode($categories) }}
}" x-init="$watch('darkMode', value => localStorage.setItem('darkMode', value))" :class="{ 'dark': darkMode }"
    class="bg-gray-100 dark:bg-gray-900 transition-colors duration-300 ">
    <div class="bg-gray-100 dark:bg-gray-900 min-h-screen">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <header class="flex justify-between items-center mb-12">
                <div class="flex items-center space-x-4">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-white">XPro Library</h1>
                </div>

                <div class="flex items-center space-x-4">
                    <button @click="darkMode = !darkMode"
                        class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 transition-colors">
                        <svg x-show="!darkMode" class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 3v1m0 16v1m9-9h-1M4 12H3m3.343-3.657l-.707-.707m12.728 12.728l-.707.707M12 8a4 4 0 100 8 4 4 0 000-8z">
                            </path>
                        </svg>
                        <svg x-show="darkMode" class="w-6 h-6 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                            <path
                                d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z">
                            </path>
                        </svg>
                    </button>

                    @auth
                        <a href="{{ url('/dashboard') }}"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Dashboard
                        </a>
                    @else
                        <div class="space-x-2">
                            <a href="{{ route('login') }}"
                                class="px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-lg">Login</a>
                            <a href="{{ route('register') }}"
                                class="px-4 py-2 bg-blue-600 text-white rounded-lg">Register</a>
                        </div>
                    @endauth
                </div>
            </header>

            <!-- Main card menggunakan alpine.js -->
            <main>
                <div class="mb-12">
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                        <div class="md:flex">
                            <div class="md:flex-shrink-0 flex justify-center items-center">
                                <img class="h-48 w-full object-contain md:w-48" src="{{ asset('img/3 Siswa 1.png') }}"
                                    alt="Library">
                            </div>
                            <div class="p-8">
                                <div class="uppercase tracking-wide text-sm text-indigo-500 font-semibold">Welcome to
                                </div>
                                <h2 class="block mt-1 text-3xl leading-tight font-bold text-gray-900 dark:text-white">
                                    XPro Library</h2>
                                <p class="mt-2 text-gray-600 dark:text-gray-300">
                                    ini adalah contoh continius intergration dan continius development XPro Library is a digital library management system developed for the Laravel
                                    Competency Certification (Sertikom).
                                    Built with modern technology using Laravel, Livewire, and Alpine.js, this website
                                    offers a responsive and
                                    interactive user experience. Our key features include an intuitive library data
                                    visualization system.
                                </p>
                                <div class="mt-4">
                                    <span
                                        class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">#Laravel</span>
                                    <span
                                        class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300 mr-2">#Livewire</span>
                                    <span
                                        class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 dark:text-gray-300">#AlpineJS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <div class="flex border-b dark:border-gray-700 mb-8">
                        <button @click="activeTab = 'features'"
                            :class="activeTab === 'features' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-semibold transition-colors">
                            Features
                        </button>
                        <button @click="activeTab = 'categories'"
                            :class="activeTab === 'categories' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-semibold transition-colors">
                            Book Categories
                        </button>
                        <button @click="activeTab = 'books'"
                            :class="activeTab === 'books' ? 'border-blue-600 text-blue-600' :
                                'border-transparent text-gray-500'"
                            class="px-4 py-2 border-b-2 font-semibold transition-colors">
                            Latest Books
                        </button>
                    </div>

                    <div x-show="activeTab === 'features'" class="grid md:grid-cols-3 gap-6">
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg hover:shadow-xl transition-all">
                            <svg class="w-12 h-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink" width="128px" height="133px"
                                viewBox="0 0 128 128" version="1.1">
                                <g id="surface1">
                                    <path
                                        style=" stroke:none;fill-rule:nonzero;fill:rgb(94.117647%,32.54902%,25.098039%);fill-opacity:1;"
                                        d="M 26.027344 0.136719 C 25.824219 0.214844 20.085938 3.484375 13.28125 7.394531 C 5.035156 12.136719 0.800781 14.632812 0.574219 14.867188 C 0.394531 15.070312 0.191406 15.421875 0.128906 15.644531 C -0.0429688 16.21875 -0.0546875 100.347656 0.117188 100.953125 C 0.179688 101.1875 0.382812 101.53125 0.566406 101.722656 C 1.011719 102.191406 50.238281 130.496094 50.835938 130.632812 C 51.113281 130.699219 51.425781 130.6875 51.734375 130.601562 C 52.40625 130.433594 101.503906 102.191406 101.941406 101.730469 C 102.121094 101.53125 102.324219 101.1875 102.390625 100.953125 C 102.476562 100.675781 102.507812 96.277344 102.507812 87.015625 L 102.507812 73.480469 L 114.476562 66.605469 C 125.761719 60.117188 126.453125 59.710938 126.742188 59.265625 L 127.039062 58.785156 L 127.039062 44.207031 C 127.039062 28.335938 127.070312 29.230469 126.441406 28.65625 C 126.273438 28.507812 120.523438 25.152344 113.652344 21.195312 L 101.171875 14.015625 L 99.785156 14.015625 L 87.574219 21.027344 C 80.851562 24.894531 75.136719 28.199219 74.859375 28.378906 C 74.582031 28.5625 74.25 28.902344 74.113281 29.148438 L 73.867188 29.574219 L 73.8125 43.308594 L 73.761719 57.046875 L 63.679688 62.855469 C 58.132812 66.042969 53.515625 68.683594 53.417969 68.707031 C 53.238281 68.757812 53.226562 67.449219 53.226562 42.203125 L 53.226562 15.632812 L 52.960938 15.175781 C 52.628906 14.621094 54.121094 15.507812 39.136719 6.894531 C 26.570312 -0.332031 26.871094 -0.179688 26.027344 0.136719 Z M 37.578125 10.65625 C 42.835938 13.671875 47.136719 16.167969 47.136719 16.199219 C 47.136719 16.230469 42.527344 18.894531 36.894531 22.132812 L 26.644531 28.015625 L 16.414062 22.132812 C 10.792969 18.894531 6.1875 16.230469 6.1875 16.199219 C 6.1875 16.167969 10.785156 13.503906 16.40625 10.273438 L 26.613281 4.402344 L 27.316406 4.785156 C 27.710938 5 32.332031 7.640625 37.578125 10.65625 Z M 110.730469 24.191406 C 116.265625 27.378906 120.84375 30.011719 120.886719 30.054688 C 121.003906 30.160156 100.703125 41.828125 100.425781 41.816406 C 100.148438 41.808594 80.097656 30.246094 80.105469 30.105469 C 80.117188 29.945312 100.289062 18.339844 100.492188 18.371094 C 100.585938 18.394531 105.195312 21.015625 110.730469 24.191406 Z M 14.828125 25.875 L 24.585938 31.492188 L 24.640625 59.304688 L 24.691406 87.121094 L 24.929688 87.496094 C 25.054688 87.695312 25.289062 87.964844 25.460938 88.089844 C 25.621094 88.207031 31.050781 91.300781 37.523438 94.953125 L 49.28125 101.59375 L 49.28125 113.359375 C 49.28125 119.816406 49.238281 125.113281 49.183594 125.113281 C 49.140625 125.113281 38.976562 119.296875 26.601562 112.175781 L 4.105469 99.238281 L 4.074219 59.464844 L 4.054688 19.703125 L 4.554688 19.980469 C 4.84375 20.132812 9.460938 22.785156 14.828125 25.875 Z M 49.28125 45.453125 L 49.28125 71.082031 L 48.886719 71.339844 C 48.351562 71.679688 28.8125 82.910156 28.746094 82.910156 C 28.714844 82.910156 28.691406 71.339844 28.691406 57.195312 L 28.703125 31.492188 L 38.910156 25.621094 C 44.523438 22.390625 49.152344 19.769531 49.207031 19.789062 C 49.246094 19.8125 49.28125 31.363281 49.28125 45.453125 Z M 88.222656 39.558594 L 98.453125 45.441406 L 98.453125 57.101562 C 98.453125 68.164062 98.441406 68.757812 98.273438 68.695312 C 98.164062 68.652344 93.535156 66 87.980469 62.800781 L 77.867188 56.992188 L 77.867188 45.335938 C 77.867188 38.917969 77.898438 33.675781 77.929688 33.675781 C 77.972656 33.675781 82.601562 36.320312 88.222656 39.558594 Z M 123.09375 45.261719 C 123.09375 51.644531 123.050781 56.910156 123.007812 56.960938 C 122.933594 57.078125 102.699219 68.738281 102.570312 68.738281 C 102.539062 68.738281 102.507812 63.496094 102.507812 57.078125 L 102.507812 45.421875 L 112.714844 39.546875 C 118.335938 36.320312 122.964844 33.675781 123.007812 33.675781 C 123.0625 33.675781 123.09375 38.886719 123.09375 45.261719 Z M 86.230469 66.46875 C 94.835938 71.421875 96.320312 72.308594 96.171875 72.425781 C 96.074219 72.488281 92.8125 74.363281 88.929688 76.582031 C 85.046875 78.796875 74.988281 84.53125 66.570312 89.328125 L 51.273438 98.054688 L 50.785156 97.789062 C 47.863281 96.191406 30.910156 86.546875 30.910156 86.472656 C 30.902344 86.3125 75.765625 60.53125 75.945312 60.597656 C 76.03125 60.628906 80.660156 63.269531 86.230469 66.46875 Z M 98.433594 87.558594 L 98.398438 99.238281 L 75.914062 112.175781 C 63.542969 119.296875 53.375 125.113281 53.324219 125.113281 C 53.269531 125.113281 53.226562 120.359375 53.226562 113.359375 L 53.226562 101.59375 L 75.765625 88.742188 C 88.148438 81.675781 98.324219 75.890625 98.378906 75.878906 C 98.421875 75.878906 98.441406 81.132812 98.433594 87.558594 Z M 98.433594 87.558594 " />
                                </g>
                            </svg>

                            <h3 class="text-xl font-bold mb-2 dark:text-white">Laravel</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Laravel is a powerful and elegant PHP framework designed to simplify web application
                                development. Laravel is ideal for building modern, secure, and scalable web
                                applications.
                            </p>

                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg hover:shadow-xl transition-all">
                            <svg class="w-12 h-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg" width="128"
                                height="128" viewBox="0 0 128 128">
                                <path style="fill-rule:evenodd;fill:#fb70a9;fill-opacity:1"
                                    d="M108.566 83.547c-1.937 2.926-3.406 6.527-7.34 6.527-6.624 0-6.98-10.203-13.609-10.203-6.625 0-6.265 10.203-12.887 10.203-6.625 0-6.98-10.203-13.609-10.203-6.625 0-6.266 10.203-12.887 10.203-6.625 0-6.98-10.203-13.605-10.203-6.629 0-6.27 10.203-12.89 10.203-2.083 0-3.544-1.008-4.778-2.39-4.738-8.239-7.465-17.895-7.465-28.22 0-30.222 23.367-54.722 52.191-54.722 28.825 0 52.192 24.5 52.192 54.723 0 8.64-1.91 16.816-5.313 24.082Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#4e56a6;fill-opacity:1"
                                    d="M40.844 78.145v22.668c0 4.066-3.301 7.363-7.371 7.363a7.365 7.365 0 0 1-7.371-7.364V73.45c1.375-2.523 2.945-4.707 5.78-4.707 4.61 0 6.223 5.79 8.962 9.403Zm27.843 1.183v35.844a8.185 8.185 0 0 1-8.187 8.183c-4.523 0-8.191-3.664-8.191-8.183v-40.57c1.543-2.973 3.132-5.86 6.39-5.86 5.16 0 6.563 7.242 9.989 10.586Zm26.211-.66v26.023c0 4.067-3.3 7.364-7.37 7.364-4.071 0-7.372-3.297-7.372-7.364V72.707c1.281-2.195 2.809-3.965 5.364-3.965 4.84 0 6.375 6.38 9.378 9.926Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#000;fill-opacity:.298039"
                                    d="M40.844 85.094c-1.309-1.602-2.856-2.79-5.094-2.79-5.316 0-6.293 6.696-9.648 9.712V63.145a7.365 7.365 0 0 1 7.37-7.364c4.071 0 7.372 3.297 7.372 7.364Zm27.843.515c-1.394-1.855-3.023-3.304-5.496-3.304-5.914 0-6.457 8.285-10.882 10.578v-12.77c0-4.52 3.668-8.183 8.191-8.183a8.185 8.185 0 0 1 8.188 8.183Zm26.211-1.433c-1.136-1.117-2.48-1.871-4.265-1.871-5.73 0-6.418 7.777-10.477 10.343V66.734a7.371 7.371 0 0 1 14.742 0Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#fb70a9;fill-opacity:1"
                                    d="M108.566 83.547c-1.937 2.926-3.406 6.527-7.34 6.527-6.624 0-6.98-10.203-13.609-10.203-6.625 0-6.265 10.203-12.887 10.203-6.625 0-6.98-10.203-13.609-10.203-6.625 0-6.266 10.203-12.887 10.203-6.625 0-6.98-10.203-13.605-10.203-6.629 0-6.27 10.203-12.89 10.203-2.083 0-3.544-1.008-4.778-2.39-4.738-8.239-7.465-17.895-7.465-28.22 0-30.222 23.367-54.722 52.191-54.722 28.825 0 52.192 24.5 52.192 54.723 0 8.64-1.91 16.816-5.313 24.082Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#e24ca6;fill-opacity:1"
                                    d="M97.273 88.984c13.676-20.332 14.028-42.879 1.059-67.652 9.613 9.844 15.547 23.348 15.547 38.25 0 8.61-1.98 16.75-5.508 23.992-2.004 2.91-3.531 6.5-7.61 6.5a5.947 5.947 0 0 1-3.488-1.09Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#fff;fill-opacity:1"
                                    d="M58.89 73.117c18.15 0 25.79-10.52 25.79-25.46 0-14.942-11.547-28.692-25.79-28.692-14.245 0-25.792 13.75-25.792 28.691 0 14.942 7.64 25.461 25.793 25.461Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#030776;fill-opacity:1"
                                    d="M61.625 37.836c0 5.89-4.332 10.668-9.672 10.668-5.344 0-9.672-4.777-9.672-10.668 0-5.89 4.328-10.668 9.672-10.668 5.34 0 9.672 4.777 9.672 10.668Zm0 0" />
                                <path style="fill-rule:evenodd;fill:#fff;fill-opacity:1"
                                    d="M55.176 35.375c0 2.719-2.164 4.922-4.836 4.922s-4.836-2.203-4.836-4.922 2.164-4.922 4.836-4.922 4.836 2.203 4.836 4.922Zm0 0" />
                            </svg>

                            <h3 class="text-xl font-bold mb-2 dark:text-white">Livewire Integration</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Livewire allows developers to create seamless and interactive experiences in Laravel
                                applications without relying heavily on JavaScript. By combining the simplicity of
                                server-side logic with the power of reactive components.
                            </p>


                        </div>
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg hover:shadow-xl transition-all">
                            <svg class="w-12 h-12 text-blue-600 mb-4" xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 128 128">
                                <path fill="#77c1d2" fill-rule="evenodd"
                                    d="M98.444 35.562 126 62.997 98.444 90.432 70.889 62.997z" clip-rule="evenodd" />
                                <path fill="#2d3441" fill-rule="evenodd"
                                    d="m29.556 35.562 57.126 56.876H31.571L2 62.997z" clip-rule="evenodd" />
                            </svg>

                            <h3 class="text-xl font-bold mb-2 dark:text-white">Alpine.js</h3>
                            <p class="text-gray-600 dark:text-gray-300">
                                Alpine.js provides a simple and lightweight development experience for building dynamic
                                user interfaces. With an intuitive syntax, it offers the flexibility of larger
                                frameworks without the additional overhead, making it an excellent choice for modern
                                projects requiring interactivity and minimal complexity.
                            </p>

                        </div>
                    </div>

                    <div x-show="activeTab === 'categories'" class="grid md:grid-cols-3 gap-4">
                        <template x-for="category in categories">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                <span x-text="category.nama_kategori" class="font-bold dark:text-white"></span>
                                <span x-text="category.books_count + ' books'"
                                    class="text-gray-500 dark:text-gray-400 ml-2"></span>
                            </div>
                        </template>
                    </div>

                    <div x-show="activeTab === 'books'" class="grid md:grid-cols-3 gap-4">
                        <template x-for="book in books">
                            <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
                                <h4 x-text="book.judul_buku" class="font-bold dark:text-white"></h4>
                                <p x-text="book.penulis" class="text-gray-600 dark:text-gray-300"></p>
                                <span x-text="book.tahun_terbit"
                                    class="text-sm text-gray-500 dark:text-gray-400"></span>
                            </div>
                        </template>
                    </div>
                </div>
                {{-- call to action --}}
                <div class="mt-8 flex justify-center">
                    <a href="{{ route('member.book-management') }}"
                        class="group relative inline-flex items-center overflow-hidden rounded-full bg-blue-600 px-8 py-3 text-white focus:outline-none focus:ring active:bg-blue-500"
                        x-data="{ isHovered: false }" @mouseenter="isHovered = true" @mouseleave="isHovered = false">
                        <span class="absolute -end-full transition-all group-hover:end-4"
                            :class="{ 'end-4': isHovered }">
                            <svg class="h-5 w-5 rtl:rotate-180" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 8l4 4m0 0l-4 4m4-4H3" />
                            </svg>
                        </span>
                        <span class="text-sm font-medium transition-all group-hover:me-4"
                            :class="{ 'me-4': isHovered }">
                            Explore Books
                        </span>
                    </a>
                </div>
                <div class="mt-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-8">
                    <h3 class="text-2xl font-bold mb-4 text-gray-800 dark:text-white">About the Developer</h3>
                    <div class="flex items-center space-x-4">
                        <img src="https://ridwan-porto.my.id/image/MUHAMMAD%20RIDWAN%20(2).webp" alt="Muhammad Ridwan"
                            class="w-16 h-16 rounded-full">
                        <div>
                            <p class="text-lg font-semibold text-gray-800 dark:text-white">Muhammad Ridwan</p>
                            <p class="text-gray-600 dark:text-gray-300">XI RPL 1</p>
                            <a href="https://ridwan-porto.my.id/" target="_blank"
                                class="text-blue-600 hover:underline">Portfolio Website</a>
                        </div>
                    </div>
                    <p class="mt-4 text-gray-600 dark:text-gray-300">
                        This website was crafted with passion by Muhammad Ridwan, a talented student from XI RPL 1.
                        Explore more of his work and projects on his portfolio website.
                    </p>
                </div>
            </main>

            <footer class="mt-8 text-center text-gray-500 dark:text-gray-400">
                © {{ date('Y') }} XPro Library - Powered by Laravel
            </footer>
        </div>
    </div>
</body>

</html>
