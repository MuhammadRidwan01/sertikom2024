<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'judul_buku',
        'penulis',
        'tahun_terbit',
        'kategori',
        'status',
        'deskripsi',
        'harga'
    ];

    // public function category()
    // {
    //     return $this->belongsTo(Category::class)->withDefault([
    //         'nama_kategori' => 'Kategori Tidak Tersedia'
    //     ]);
    // }
    public function category()
{
    return $this->belongsTo(Category::class, 'kategori', 'id');
}


    // Scope untuk buku yang bisa dipinjam
    public function scopeAvailableForLoan($query)
    {
        return $query->where('status', 'tersedia')
                     ->whereHas('category', function($q) {
                         $q->where('status', 'active');
                     });
    }
    public function loans()
    {
        return $this->hasMany(Loan::class);
    }
}
