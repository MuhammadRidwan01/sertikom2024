<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\Factories\HasFactory;

// app/Models/Loan.php
class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'book_id',
        'user_id',
        'tanggal_kembali',
        'tanggal_pinjam',
        'status_peminjaman'
    ];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function validateLoan($user_id, $book_id)
    {
        // Cek jumlah buku yang sedang dipinjam
        $activeLoanCount = self::where('user_id', $user_id)
            ->where('status_peminjaman', 'aktif')
            ->count();

        if ($activeLoanCount >= 3) {
            return [
                'success' => false,
                'message' => 'Anda sudah mencapai batas maksimal peminjaman (3 buku).'
            ];
        }

        // Cek ketersediaan buku untuk dipinjam
        $book = Book::findOrFail($book_id);

        if ($book->status !== 'tersedia') {
            throw ValidationException::withMessages([
                'book' => 'Buku tidak tersedia untuk dipinjam.'
            ]);
        }

        // Cek kategori buku
        if ($book->category->status !== 'active') {
            throw ValidationException::withMessages([
                'book' => 'Buku tidak dapat dipinjam karena kategori tidak aktif.'
            ]);
        }

        return true;
    }


    // Metode returnBook dan pencatatan denda
    public function returnBook()
    {
        $denda = 0;
        if (now()->greaterThan($this->tanggal_kembali)) {
            $denda = 10000; // Denda 10.000 per hari keterlambatan
            Fine::createFine($this, $denda);
        }

        $this->update([
            'status_peminjaman' => 'selesai',
            'tanggal_kembali' => now()
        ]);

        $book = $this->book;
        $book->update(['status' => 'tersedia']);

        return $denda;
    }

}
