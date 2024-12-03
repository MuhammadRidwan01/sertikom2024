<?php
namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class BookManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactiveCategoryBooks = false;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingShowInactiveCategoryBooks()
    {
        $this->resetPage();
    }

    #[On('books-updated')]
    public function loadBooks()
    {
        return Book::with(['category' => function($query) {
            $query->select('id', 'status', 'nama_kategori'); // Load only necessary fields
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
        ->paginate(11);

    }

    public function render()
    {
        return view('livewire.book-management', [
            'books' => $this->loadBooks()
        ]);
    }

    // public function goToProfile()
    // {
            // untuk pindah halaman tanpa reload
    //     return $this->redirect(route('password.request'), navigate: true);
    // }
}
