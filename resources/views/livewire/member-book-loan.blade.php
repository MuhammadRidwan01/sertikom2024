<div x-data="{ activeTab: 'available', showModal: false, selectedBook: null }" class="container mx-auto px-4 py-8 bg-gray-100 dark:bg-gray-900">
    <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-8">Peminjaman Buku</h1>

    <div class="mb-8">
        <div class="flex space-x-4 mb-4">
            <button @click="activeTab = 'available'"
                :class="{ 'bg-blue-500 text-white': activeTab === 'available', 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300': activeTab !== 'available' }"
                class="px-4 py-2 rounded-md transition duration-300 ease-in-out">
                Buku Tersedia
            </button>
            <button @click="activeTab = 'borrowed'"
                :class="{ 'bg-blue-500 text-white': activeTab === 'borrowed', 'bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300': activeTab !== 'borrowed' }"
                class="px-4 py-2 rounded-md transition duration-300 ease-in-out">
                Buku Dipinjam
            </button>
        </div>

        <div x-show="activeTab === 'available'" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100">
            <div class="relative">
                <input type="text" wire:model.live.debounce.300ms="searchTerm"
                    placeholder="Cari judul buku atau penulis"
                    class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 transition duration-150 ease-in-out">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div x-show="showModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
        aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div x-show="showModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity dark:bg-gray-900 dark:bg-opacity-75" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div x-show="showModal" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                                Pinjam Buku: <span x-text="selectedBook?.judul_buku"></span>
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'Penulis: ' + selectedBook?.penulis"></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="'Tahun Terbit: ' + selectedBook?.tahun_terbit">
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400"
                                    x-text="'Kategori: ' + selectedBook?.category.nama_kategori"></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Deskripsi:</p>
                                <p class="text-sm text-gray-700 dark:text-gray-300" x-text="selectedBook?.deskripsi"></p>
                            </div>
                                <div class="w-full ">
                                    <label for="tanggal_kembali" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal Kembali</label>
                                    <input type="date" id="tanggal_kembali" wire:model="tanggal_kembali" min="{{ $todayDate }}"
                                        class="mt-2 w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:focus:ring-indigo-400 dark:focus:border-indigo-400 dark:bg-gray-700 dark:text-gray-300 sm:text-sm">
                                </div>

                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="showModal = false" wire:click="tambahKeKeranjang"
                        class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 sm:ml-3 sm:w-auto sm:text-sm">
                        Tambah ke keranjang
                    </button>

                    <button type="button" @click="showModal = false"
                        class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div x-show="activeTab === 'available'" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-90"
        x-transition:enter-end="opacity-100 transform scale-100">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($books as $book)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="p-6 flex flex-col h-full">
                        <h3 class="text-2xl font-semibold mb-2 text-gray-800 dark:text-gray-100 truncate">{{ $book->judul_buku }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 truncate">{{ $book->penulis }}</p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-semibold px-2 py-1 bg-green-100 dark:bg-green-800 text-green-800 dark:text-green-100 rounded-full">
                                {{ $book->category->nama_kategori }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Terbit {{ $book->tahun_terbit }}
                            </span>
                        </div>
                        <h4 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100 truncate">
                            {{
                                (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))
                                ->formatCurrency($book->harga, 'IDR')
                            }}
                        </h4>
                        <div class="flex-grow"></div>
                        <button
                            x-on:click.prevent="showModal = true; selectedBook = @js($book)"
                            wire:click="selectBook({{ $book->id }})"
                            class="w-full px-4 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:shadow-lg hover:from-blue-600  hover:to-blue-700 transform hover:scale-105 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-400 focus:ring-opacity-50">
                            Pinjam Buku
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center bg-white rounded-lg shadow-md p-8">
                    <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 text-lg text-center">Tidak ada buku yang tersedia.</p>
                    <p class="text-gray-400 text-sm text-center mt-2">Coba ubah kriteria pencarian Anda.</p>
                </div>
            @endforelse
        </div>


        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </div>

    <div x-show="activeTab === 'borrowed'" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100">
                    <div class="my-6">
                        <h2 class="text-2xl font-bold text-gray-800 dark:text-white">List pinjaman</h2>
                        <p class="text-gray-600 dark:text-gray-400">Pinjaman aktif: {{ $userLoans->where('status_peminjaman', 'aktif')->count() }} buku</p>
                        <p class="text-gray-600 dark:text-gray-400">Total: {{ $userLoans->count() }} buku</p>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse($userLoans as $loan)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300">
                                <div class="p-6">
                                    <h3 class="text-xl font-semibold mb-2 text-gray-800 dark:text-gray-100">{{ $loan->book->judul_buku }}</h3>
                                    <div class="flex justify-between items-center mb-4">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Dipinjam: {{ $loan->tanggal_pinjam }}</span>
                                        <span class="text-sm text-gray-600 dark:text-gray-400">Kembali: {{ $loan->tanggal_kembali }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $loan->status_peminjaman === 'aktif' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' }}">
                                            {{ $loan->status_peminjaman }}
                                        </span>
                                        @if ($loan->status_peminjaman == 'aktif')
                                            <button wire:click="kembalikanBuku({{ $loan->id }})"
                                                class="px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg hover:bg-blue-600 dark:hover:bg-blue-700 transition duration-300 ease-in-out">
                                                Kembalikan
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
                                <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg text-center">Anda belum meminjam buku.</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm text-center mt-2">Silakan pinjam buku untuk melihat daftar peminjaman Anda.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
</div>

<script>
    document.addEventListener('livewire:load', function() {
        Livewire.on('bookBorrowed', function() {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: 'Buku berhasil dipinjam!'
            }));
        });

        Livewire.on('bookReturned', function(data) {
            window.dispatchEvent(new CustomEvent('notify', {
                detail: data.message
            }));
        });
    });
</script>
