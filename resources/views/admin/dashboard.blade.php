<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mb-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 flex flex-col md:flex-row items-center">
                    <img src="/img/Untitled-1 1 (1).png" alt="Library Logo" class="w-full md:w-1/2 mb-4 md:mb-0 md:mr-6">
                    <div class="text-center md:text-left">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-2">Selamat datang di Perpustakaan!</h2>
                        <p class="text-lg text-gray-600 dark:text-gray-400">
                            Halo, Admin <span class="font-semibold">{{ Auth::user()->name }}</span>. Selamat datang kembali di dashboard perpustakaan.
                        </p>
                        <p class="text-sm text-green-700 dark:text-green-400 mt-2">
                            Anda memiliki akses penuh untuk mengelola sistem perpustakaan. Gunakan menu di sidebar untuk navigasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-4">Ringkasan Database</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 dark:bg-blue-800 p-4 rounded-lg">
                            <h4 class="font-bold">Pengguna</h4>
                            <p class="text-2xl">{{ $users }}</p>
                        </div>
                        <div class="bg-green-100 dark:bg-green-800 p-4 rounded-lg">
                            <h4 class="font-bold">Total Buku</h4>
                            <p class="text-2xl">{{ $books }}</p>
                        </div>
                        <div class="bg-yellow-100 dark:bg-yellow-800 p-4 rounded-lg">
                            <h4 class="font-bold">Kategori</h4>
                            <p class="text-2xl">{{ $categories }}</p>
                        </div>
                        <div class="bg-red-100 dark:bg-red-800 p-4 rounded-lg">
                            <h4 class="font-bold">Pinjaman</h4>
                            <p class="text-2xl">{{ $loans }}</p>
                        </div>
                        <div class="bg-purple-100 dark:bg-purple-800 p-4 rounded-lg">
                            <h4 class="font-bold">Denda</h4>
                            <p class="text-2xl">{{ $fines }}</p>
                        </div>
                        <div class="bg-indigo-100 dark:bg-indigo-800 p-4 rounded-lg">
                            <h4 class="font-bold">Pinjaman aktif</h4>
                            <p class="text-2xl">{{ $activeLoans }}</p>
                        </div>
                        <div class="bg-pink-100 dark:bg-pink-800 p-4 rounded-lg">
                            <h4 class="font-bold">Denda belum di bayar</h4>
                            <p class="text-2xl">{{ $overdueFines }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Buku populer</h3>
                            <ul class="list-disc pl-5">
                                @foreach ($popularBooks as $book)
                                    <li>{{ $book->judul_buku }} ({{ $book->loans_count }} Di pinjam)</li>
                                @endforeach
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Rangking peminjam</h3>
                            <ul class="list-disc pl-5">
                                @foreach ($topBorrowers as $user)
                                    <li>{{ $user->name }} ({{ $user->loans_count }} Pinjaman)</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-semibold mb-2">Pinjaman Terbaru</h3>
                        <table class="min-w-full">
                            <thead>
                                <tr>
                                    <th class="text-left">Pengguna</th>
                                    <th class="text-left">Buku</th>
                                    <th class="text-left">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($recentLoans as $loan)
                                    <tr>
                                        <td>{{ $loan->user->name }}</td>
                                        <td>{{ $loan->book->judul_buku }}</td>
                                        <td>{{ $loan->created_at->format('Y-m-d') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
