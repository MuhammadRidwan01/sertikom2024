<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Fine;
use App\Models\Loan;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
{
    $data = [
        'users' => User::count(),
        'books' => Book::count(),
        'categories' => Category::count(),
        'loans' => Loan::count(),
        'fines' => Fine::count(),
        'activeLoans' => Loan::where('status_peminjaman', 'aktif')->count(),
        'overdueFines' => Fine::where('status', 'unpaid')->count(),
        'popularBooks' => Book::withCount('loans')->orderBy('loans_count', 'desc')->take(5)->get(),
        'recentLoans' => Loan::with('user', 'book')->latest()->take(10)->get(),
        'topBorrowers' => User::withCount('loans')->orderBy('loans_count', 'desc')->take(5)->get(),
        'monthlyLoanStats' => Loan::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get(),
    ];

    return view('admin.dashboard', $data);
}
}
