<?php

namespace App\Models;

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
        Book::whereNull('category_id')
            ->update(['category_id' => $this->id]);
    }
}
