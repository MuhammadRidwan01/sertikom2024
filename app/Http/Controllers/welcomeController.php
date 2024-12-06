<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;

class welcomeController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('books')->latest()->get();//+
    $books = Book::latest()->take(5)->get();//+
    return view('welcome', compact('categories', 'books'));//+
    }
}
