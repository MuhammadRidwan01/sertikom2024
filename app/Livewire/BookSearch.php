<?php
namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;
use Livewire\WithPagination;

class BookSearch extends Component
{
    use WithPagination;

    public $search = '';
    public $showInactiveCategoryBooks = false;
    public $searchResults = [];
    public $isSearchModalOpen = false;

    public function updatedShowInactiveCategoryBooks()
    {
        // Automatically refresh search when checkbox is toggled
        $this->resetPage();
        $this->performSearch();
    }

    public function updatedSearch($value)
    {
        // Trigger search and open modal when search has at least 2 characters
        if (strlen($value) >= 2) {
            $this->searchResults = $this->performSearch();
            $this->isSearchModalOpen = true;
        } else {
            $this->searchResults = [];
            $this->isSearchModalOpen = false;
        }
    }

    protected function performSearch()
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
        ->limit(10) // Limit search results
        ->get();
    }

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

    public function closeSearchModal()
    {
        $this->isSearchModalOpen = false;
        $this->search = '';
        $this->searchResults = [];
    }

    public function render()
    {
        return view('livewire.book-search', [
            'books' => $this->loadBooks(),
            'searchResults' => $this->searchResults
        ]);
    }
}
