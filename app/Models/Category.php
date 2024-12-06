<?php

namespace App\Models;

use App\Models\Book;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['nama_kategori', 'status'];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function reactivateCategory()
    {
        $this->update([
            'status' => 'active'
        ]);

        // Optional: Update buku yang sebelumnya berasosiasi
        Book::whereNull('kategori')
            ->update(['kategori' => $this->id]);
    }

    public function books()
{
    return $this->hasMany(Book::class, 'kategori');
}
}
