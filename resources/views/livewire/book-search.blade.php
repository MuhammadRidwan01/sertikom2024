<div>
    <div class="max-w-3xl mx-auto p-2 sm:p-4">
        <div class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-4">
            <div class="relative flex-grow">
                <input
                    type="text"
                    wire:model.live.debounce.300ms="search"
                    placeholder="Cari buku"
                    class="w-full  sm:px-4 sm:py-3 pl-10 sm:pl-12 pr-4 text-sm sm:text-base text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-150 ease-in-out"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 sm:h-5 sm:w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                    </svg>
                </div>
            </div>

            <div class="flex items-center hidden sm:block">
                <label class="inline-flex items-center cursor-pointer bg-white px-3 py-2 sm:px-4 sm:py-2 rounded-lg shadow-sm border border-gray-300 hover:bg-gray-50 transition duration-150 ease-in-out text-xs sm:text-sm">
                    <input
                        type="checkbox"
                        wire:model.live="showInactiveCategoryBooks"
                        class="form-checkbox h-4 w-4 sm:h-5 sm:w-5 text-blue-600 rounded transition duration-150 ease-in-out"
                    >
                    <span class="ml-2 font-medium text-gray-700">Kategori Tidak Aktif</span>
                </label>
            </div>
        </div>
    </div>

    @if($isSearchModalOpen && count($searchResults) > 0)
    <div
        x-data
        @click.self="$wire.closeSearchModal()"
        class="fixed inset-0 z-50 overflow-y-auto bg-black bg-opacity-50 flex items-center justify-center"
    >
        <div class="bg-white w-11/12 md:w-3/4 lg:w-1/2 max-h-[80vh] rounded-lg shadow-xl overflow-y-auto">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Hasil Pencarian</h3>
                    <div class="flex items-center space-x-4">
                        <button
                            wire:click="closeSearchModal"
                            class="text-gray-500 hover:text-gray-700"
                        >
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="space-y-3">
                    @foreach($searchResults as $book)
                        <div
                            wire:click="selectBook({{ $book->id }})"
                            class="p-3 hover:bg-gray-100 cursor-pointer border rounded-md"
                        >
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-bold text-gray-800">{{ $book->judul_buku }}</p>
                                    <p class="text-sm text-gray-600">{{ $book->penulis }}</p>
                                    <span class="text-xs px-2 py-1 rounded-full
                                        {{ $book->category && $book->category->status !== 'active' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ $book->category ? $book->category->nama_kategori : 'Tanpa Kategori' }}
                                    </span>
                                </div>
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
