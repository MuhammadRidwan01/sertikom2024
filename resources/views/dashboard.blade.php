<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Anggota') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row items-center">
                    <img src="/img/Untitled-1 1 (1).png" alt="Logo Perpustakaan" class="w-full md:w-1/2 mb-4 md:mb-0 md:mr-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-3">Selamat datang di Library Xpro!</h2>
                        <p class="text-xl text-gray-600 dark:text-gray-400 mb-4">
                            Halo, <span class="font-semibold">{{ Auth::user()->name }}</span>. Senang melihat Anda
                            kembali di dashboard perpustakaan.
                        </p>
                        <x-nav-link :href="route('member.books')"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 border text-white border-transparent rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            wire:navigate>
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                </path>
                            </svg>
                            Pinjam Buku
                        </x-nav-link>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">Buku Dipinjam</h3>
                            <p class="text-3xl font-bold text-blue-600 dark:text-blue-300">{{ $borrowedBooksCount }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-200 mb-2">Buku Tersedia</h3>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-300">{{ $availableBooksCount }}
                            </p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg shadow">
                            <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-2">Denda</h3>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-300">
                                {{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($totalFines, 'IDR') }}
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Buku yang Dipinjam
                            </h3>
                            <ul class="space-y-2">
                                @forelse($borrowedBooks as $book)
                                    <li class="flex justify-between items-center">
                                        <span class="text-white dark:text-gray-300">{{ $book['judul_buku'] }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">Kembali:
                                            {{ $book['tanggal_kembali'] }} ({{ $book['sisa_hari'] }} Hari)</span>
                                    </li>
                                @empty
                                    <li class="text-gray-500 dark:text-gray-400">Tidak ada buku yang dipinjam saat ini.
                                    </li>
                                @endforelse
                            </ul>
                        </div>

                        <div class="bg-gray-100 dark:bg-gray-700 p-6 rounded-lg shadow">
                            <h3 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Rekomendasi Buku
                            </h3>
                            <ul class="space-y-2">
                                @forelse($recommendedBooks as $book)
                                    <li class="flex justify-between items-center">
                                        <span class="text-gray-700 dark:text-gray-300">{{ $book->judul_buku }}</span>
                                        <span
                                            class="text-sm text-gray-500 dark:text-gray-400">{{ $book->penulis }}</span>
                                    </li>
                                @empty
                                    <li class="text-gray-500 dark:text-gray-400">Tidak ada rekomendasi buku saat ini.
                                    </li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:admin.admin-book-loan />
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:admin.admin-book-management />
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:manage-categories />
            </div>
        </div>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <livewire:member-book-loan />
            </div>
        </div>
    </div>
</x-app-layout> --}}
