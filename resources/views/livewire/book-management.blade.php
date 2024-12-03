<div class="bg-gray-100 dark:bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-8">Manajemen Buku</h1>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="relative flex-grow md:mr-4 mb-4 md:mb-0">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari buku berdasarkan judul atau penulis"
                        class="w-full px-4 py-2 pl-10 pr-4 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 focus:bg-white dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <label class="inline-flex items-center cursor-pointer">
                    <input
                    type="checkbox"
                    wire:model.live="showInactiveCategoryBooks"
                        class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-400 transition duration-150 ease-in-out"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Tampilkan Kategori Tidak Aktif</span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($books as $book)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transform hover:shadow-lg transition-all duration-300
                    {{ $book->category && $book->category->status !== 'active' ? 'ring-2 ring-red-300 dark:ring-red-700' : '' }}">
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-200 truncate">
                            {{ $book->judul_buku }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                            {{ $book->penulis }}
                        </p>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full
                                {{ $book->category && $book->category->status !== 'active' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' }}">
                                {{ $book->category ? $book->category->nama_kategori : 'Tanpa Kategori' }}
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                Terbit {{ $book->tahun_terbit }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            @if(!$book->category || $book->category->status !== 'active' || $book->status !== 'tersedia')
                                <span class="px-3 py-1 bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 text-xs font-medium rounded-full">
                                    @if(!$book->category)
                                        Tanpa Kategori
                                    @elseif($book->category->status !== 'active')
                                        Kategori Tidak Aktif
                                    @else
                                        Dipinjam
                                    @endif
                                </span>
                            @else
                                <span class="px-3 py-1 bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 text-xs font-medium rounded-full">
                                    Tersedia
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
                    <svg class="h-16 w-16 text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-lg text-center">Tidak ada buku yang ditemukan.</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm text-center mt-2">Coba ubah kriteria pencarian Anda.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-8">
            {{ $books->links() }}
        </div>
    </div>
</div>
