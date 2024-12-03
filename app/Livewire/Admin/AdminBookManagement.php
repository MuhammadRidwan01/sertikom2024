<?php

namespace App\Livewire\Admin;

use App\Models\Book;
use Livewire\Component;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class AdminBookManagement extends Component
{
    use WithPagination;
    use LivewireAlert;

    public $search = '';
    public $showInactiveCategoryBooks = false;
    public $showingAddBookModal = false;
    public $isConfirmModalOpen = false;
    public $bookOnDelete = null;
    public $editingBook = null;
    public $judul_buku, $penulis, $tahun_terbit, $kategori, $status, $deskripsi, $harga;

    // Properti untuk buku baru
    public $new_judul_buku;
    public $new_penulis;
    public $new_tahun_terbit;
    public $new_kategori;
    public $new_deskripsi;
    public $new_harga;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShowInactiveCategoryBooks()
    {
        $this->resetPage();
    }

    public function editBook($bookId)
    {
        $this->editingBook = Book::findOrFail($bookId);
        $this->judul_buku = $this->editingBook->judul_buku;
        $this->penulis = $this->editingBook->penulis;
        $this->tahun_terbit = $this->editingBook->tahun_terbit;
        $this->kategori = $this->editingBook->kategori;
        $this->status = $this->editingBook->status;
        $this->deskripsi = $this->editingBook->deskripsi;
        $this->harga = $this->editingBook->harga;
    }

    public function opendirConfirmModal($bookId)
    {
        $this->bookOnDelete = Book::findOrFail($bookId);
        $this->isConfirmModalOpen = true;
    }

    public function hideConfirmModal()
    {
        $this->isConfirmModalOpen = false;
        $this->bookOnDelete = null;
    }
    public function deleteBook()
    {
        if ($this->bookOnDelete) {
            $judul_buku = $this->bookOnDelete->judul_buku;
            $this->bookOnDelete->delete();
            $this->dispatch('books-updated');
            $this->alert('success', 'Buku "' . $judul_buku . '" berhasil dihapus!');
            $this->hideConfirmModal();
        } else {
            $this->alert('error', 'Buku tidak ditemukan.');
        }
    }

    public function updateBook()
    {
        try {
            //code...

        $this->validate([
            'judul_buku' => 'required|string|max:255',
            'penulis' => 'required|string|max:255',
            'tahun_terbit' => 'required|integer|min:1900|max:2099',
            'kategori' => 'required|exists:categories,id',
            'status' => 'required|in:tersedia,dipinjam',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
        ]);

        $this->editingBook->update([
            'judul_buku' => $this->judul_buku,
            'penulis' => $this->penulis,
            'tahun_terbit' => $this->tahun_terbit,
            'kategori' => $this->kategori,
            'status' => $this->status,
            'deskripsi' => $this->deskripsi,
            'harga' => $this->harga,
        ]);
        $judul_buku = $this->editingBook->judul_buku;
        $this->resetEditForm();
        $this->dispatch('books-updated');
        $this->alert('success','Buku: ['. $judul_buku .'] berhasil diperbarui!');
    } catch (\Throwable $th) {
        $this->alert('error', 'Pastikan Semua form terisi dan format yang valid!');
    }
    }

    public function resetEditForm()
    {
        $this->editingBook = null;
        $this->judul_buku = '';
        $this->penulis = '';
        $this->tahun_terbit = null;
        $this->kategori = null;
        $this->status = 'tersedia';
        $this->deskripsi = '';
        $this->harga = null;
    }

    #[On('books-updated')]
    public function loadBooks()
    {
        return Book::with(['category' => function($query) {
            $query->select('id', 'status', 'nama_kategori');
        }])
        ->when($this->search, function ($query) {
            return $query->where(function ($q) {
                $q->where('judul_buku', 'like', '%' . $this->search . '%')
                  ->orWhere('penulis', 'like', '%' . $this->search . '%');
            });
        })
        ->when(!$this->showInactiveCategoryBooks, function ($query) {
            return $query->whereHas('category', function ($q) {
                $q->where('status', 'active');
            });
        })
        ->latest()
        ->paginate(9);
    }

    public function showAddBookModal()
    {
        $this->showingAddBookModal = true;
    }

    public function hideAddBookModal()
    {
        $this->showingAddBookModal = false;
        $this->resetAddBookForm();
    }

    public function addBook()
    {
        try {
            //code...

        $this->validate([
            'new_judul_buku' => 'required|string|max:255',
            'new_penulis' => 'required|string|max:255',
            'new_tahun_terbit' => 'required|integer|min:1900|max:2099',
            'new_kategori' => 'required|exists:categories,id',
            'new_deskripsi' => 'nullable|string',
            'new_harga' => 'required|numeric|min:0',
        ]);

        // dd('new_kategori', $this->new_kategori);
        Book::create([
            'judul_buku' => $this->new_judul_buku,
            'penulis' => $this->new_penulis,
            'tahun_terbit' => $this->new_tahun_terbit,
            'kategori' => $this->new_kategori,
            'deskripsi' => $this->new_deskripsi,
            'harga' => $this->new_harga,
            'status' => 'tersedia',
        ]);

        $this->hideAddBookModal();
        $this->dispatch('books-updated');
        $this->alert('success', 'Buku berhasil ditambahkan!');
    } catch (\Throwable $th) {
        $this->alert('error', 'Pastikan Semua form terisi dengan format yang valid!');
    }
    }

    private function resetAddBookForm()
    {
        $this->new_judul_buku = '';
        $this->new_penulis = '';
        $this->new_tahun_terbit = '';
        $this->new_kategori = '';
        $this->new_deskripsi = '';
        $this->new_harga = '';
    }

    public function render()
    {
        $categories = Category::where('status', 'active')->get();
        $books = $this->loadBooks();
        return view('livewire.admin.admin-book-management', [
            'books' => $books,
            'categories' => $categories,
        ]);
    }
}
