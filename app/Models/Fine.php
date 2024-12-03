<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fine extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_id',
        'jumlah_denda',
        'status'
    ];

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public static function createFine(Loan $loan, $jumlah_denda)
    {
        return self::create([
            'loan_id' => $loan->id,
            'jumlah_denda' => $jumlah_denda,
            'status' => 'belum_dibayar'
        ]);
    }
}
