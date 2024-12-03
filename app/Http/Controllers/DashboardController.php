<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Loan;
use App\Models\Fine;
use App\Models\Activity;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $data = [
            'borrowedBooksCount' => Loan::where('user_id', $user->id)->where('status_peminjaman', 'aktif')->count(),
            'availableBooksCount' => Book::where('status', 'tersedia')->count(),
            'totalFines' => Fine::where('id', $user->id)->sum('jumlah_denda'),
            'borrowedBooks' => Loan::with('book')->where('user_id', $user->id)->where('status_peminjaman', 'aktif')->latest()->take(5)->get()->map(function ($loan) {
                return [
                    'id' => $loan->id,
                    'judul_buku' => $loan->book->judul_buku,
                    'tanggal_peminjaman' => $loan->tanggal_peminjaman,
                    'tanggal_kembali' => $loan->tanggal_kembali,
                    'sisa_hari' => floor(now()->floatDiffInDays($loan->tanggal_kembali))
                ];
            }),
            'recommendedBooks' => Book::inRandomOrder()->take(5)->get(),
        ];

        return view('dashboard', $data);
    }
}
