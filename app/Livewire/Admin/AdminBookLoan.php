<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Loan;
use App\Models\User;
use App\Models\Book;
use Carbon\Carbon;

class AdminBookLoan extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedLoan;
    public $newReturnDate;

    protected $rules = [
        'newReturnDate' => 'required|date|after:today',
    ];

    public function render()
    {
        return view('livewire.admin.admin-book-loan', [
            'loans' => $this->searchLoans(),
        ]);
    }

    public function searchLoans()
    {
        return Loan::with(['user', 'book'])
            ->when($this->search, function ($query) {
                $query->whereHas('user', function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                })->orWhereHas('book', function ($q) {
                    $q->where('judul_buku', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('tanggal_pinjam', 'desc')
            ->paginate(10);
    }

    public function forceReturn($loanId)
    {
        $loan = Loan::findOrFail($loanId);
        $loan->returnBook();
        $this->dispatch('books-updated');

        $book = $loan->book;
        $book->update(['status' => 'tersedia']);

        session()->flash('message', 'Buku berhasil dikembalikan secara paksa.');
    }

    public function accLoan($loanId)
    {
        $loan = Loan::findOrFail($loanId);
        $loan->update(['status_peminjaman' => 'aktif']);

        $book = $loan->book;
        $book->update(['status' => 'dipinjam']);

        session()->flash('message', 'Buku berhasil disetujui!');
    }

    public function selectLoan($loanId)
    {
        $this->selectedLoan = Loan::findOrFail($loanId);
        $this->newReturnDate = $this->selectedLoan->tanggal_kembali;
    }

    public function closeModalselectLoan()
    {
        $this->selectedLoan = null;
        $this->newReturnDate = null;
    }

    public function updateReturnDate()
    {
        $this->validate();

        $this->selectedLoan->update([
            'tanggal_kembali' => $this->newReturnDate,
        ]);

        $this->selectedLoan = null;
        $this->newReturnDate = null;

        session()->flash('message', 'Tanggal pengembalian berhasil diubah.');
    }
}
