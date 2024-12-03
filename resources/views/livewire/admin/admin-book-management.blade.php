<div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 min-h-screen py-12">
    <div class="container mx-auto px-4 max-w-7xl">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-4xl font-extrabold text-gray-800 dark:text-gray-100">
                <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-green-500">Admin Manajemen Buku</span>
            </h1>
            <button
                wire:click="showAddBookModal"
                class="flex items-center px-5 py-2.5 bg-gradient-to-r from-blue-500 to-purple-600 text-white rounded-lg hover:scale-105 transform transition duration-300 ease-in-out shadow-lg hover:shadow-xl">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Buku
            </button>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-6 mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-4 md:space-y-0">
                <div class="relative flex-grow md:mr-4">
                    <input
                        type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari buku berdasarkan judul atau penulis"
                        class="w-full pl-10 pr-4 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 border-none rounded-full focus:ring-2 focus:ring-blue-500 focus:bg-white dark:focus:bg-gray-600 transition duration-300 ease-in-out"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
                <label class="inline-flex items-center cursor-pointer group">
                    <input
                        type="checkbox"
                        wire:model.live="showInactiveCategoryBooks"
                        class="form-checkbox h-5 w-5 text-blue-600 rounded transition duration-300 ease-in-out transform hover:scale-110"
                    >
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition duration-300">
                        Tampilkan Kategori Tidak Aktif
                    </span>
                </label>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($books as $book)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl
                    {{ $book->category && $book->category->status !== 'active' ? 'ring-2 ring-red-300 dark:ring-red-600' : '' }}">
                    <div class="p-6 relative">
                        <div class="absolute top-4 right-4">
                            <button wire:click="editBook({{ $book->id }})" class="text-gray-400 dark:text-gray-500 hover:text-blue-500 dark:hover:text-blue-400 transition duration-300 ease-in-out">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <h3 class="text-xl font-bold mb-2 text-gray-800 dark:text-gray-100 truncate">
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
                        <h4 class="text-xl font-bold mb-4 text-blue-600 dark:text-blue-400">
                            {{ (new NumberFormatter('id_ID', NumberFormatter::CURRENCY))->formatCurrency($book->harga, 'IDR') }} / Hari
                        </h4>
                        <div class="flex items-center justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                            <button wire:click="editBook({{ $book->id }})" class="px-4 py-2 bg-gradient-to-r from-blue-500 to-purple-600 text-white text-sm font-medium rounded-full hover:scale-105 transition duration-300 ease-in-out">
                                Edit
                            </button>
                            <button wire:click="opendirConfirmModal({{ $book->id }})" class="px-4 py-2 bg-gradient-to-r from-red-600 to-orange-500 text-white text-sm font-medium rounded-full hover:scale-105 transition duration-300 ease-in-out">
                                Hapus
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-lg p-12">
                    <svg class="h-20 w-20 text-gray-300 dark:text-gray-600 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 text-xl text-center font-semibold">Tidak ada buku yang ditemukan</p>
                    <p class="text-gray-400 dark:text-gray-500 text-sm text-center mt-2">Coba ubah kriteria pencarian Anda</p>
                </div>
            @endforelse
        </div>

        <div class="">
            {{ $books->links() }}
        </div>
    </div>

    <!-- Edit Book Modal -->
    @if($editingBook)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="updateBook">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                            Edit Buku
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="judul_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Buku</label>
                                <input type="text" wire:model="judul_buku" id="judul_buku" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('judul_buku') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis</label>
                                <input type="text" wire:model="penulis" id="penulis" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('penulis') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="tahun_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Terbit</label>
                                <input type="number" wire:model="tahun_terbit" id="tahun_terbit" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('tahun_terbit') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                                <select wire:model="kategori" id="kategori" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('kategori') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                <select wire:model="status" id="status" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                                    <option value="tersedia">Tersedia</option>
                                    <option value="dipinjam">Dipinjam</option>
                                </select>
                                @error('status') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                <textarea wire:model="deskripsi" id="deskripsi" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                                @error('deskripsi') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga</label>
                                <input type="number" wire:model="harga" id="harga" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('harga') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Simpan Perubahan
                        </button>
                        <button type="button" wire:click="resetEditForm" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Add Book Modal -->
    @if($showingAddBookModal)
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 dark:bg-gray-900 dark:bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form wire:submit.prevent="addBook">
                    <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100" id="modal-title">
                            Tambah Buku Baru
                        </h3>
                        <div class="mt-4 space-y-4">
                            <div>
                                <label for="new_judul_buku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Judul Buku</label>
                                <input type="text" wire:model="new_judul_buku" id="new_judul_buku" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('new_judul_buku') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="new_penulis" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Penulis</label>
                                <input type="text" wire:model="new_penulis" id="new_penulis" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('new_penulis') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="new_tahun_terbit" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tahun Terbit</label>
                                <input type="number" wire:model="new_tahun_terbit" id="new_tahun_terbit" min="1900" max="{{ date('Y') }}" step="1" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('new_tahun_terbit') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="new_kategori" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Kategori</label>
                                <select wire:model="new_kategori" id="new_kategori" class="mt-1 block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm text-gray-900 dark:text-gray-100">
                                    <option value="">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                                @error('new_kategori') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="new_deskripsi" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Deskripsi</label>
                                <textarea wire:model="new_deskripsi" id="new_deskripsi" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"></textarea>
                                @error('new_deskripsi') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label for="new_harga" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Harga</label>
                                <input type="number" wire:model="new_harga" id="new_harga" step="0.01" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                                @error('new_harga') <span class="text-red-500 dark:text-red-400 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                            Tambah Buku
                        </button>
                        <button type="button" wire:click="hideAddBookModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    {{-- modal konfirmasi hapus buku --}}
    @if($isConfirmModalOpen)
    <div x-show="isConfirmModalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
        <div @click.away="isConfirmModalOpen = false" class="bg-white p-8 rounded-lg shadow-2xl w-11/12 md:w-1/3 transform transition-all duration-300 ease-in-out" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">Konfirmasi Hapus Buku</h2>

            <p class="mb-4 text-gray-600">Apakah Anda yakin ingin menghapus Buku "<span class="font-semibold">{{ $bookOnDelete->judul_buku }}</span>"? Penulis <span class="font-semibold">{{ $bookOnDelete->penulis }}</span></p>
            <p class="text-red-500 text-sm mb-6">Catatan: Pinjaman dengan buku ini akan di paksa kembalikan.</p>

            <div class="flex justify-end space-x-3">
                <button
                    wire:click="hideConfirmModal"
                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                >
                    Batal
                </button>
                <button
                    wire:click="deleteBook"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-opacity-50"
                >
                    Hapus
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
