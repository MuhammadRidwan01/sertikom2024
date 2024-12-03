<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'total_amount', 'status', 'snap_token'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
