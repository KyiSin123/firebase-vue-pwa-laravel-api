<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Simple extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'body',
    ];

    public function scopeSort($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function user() {

        return $this->belongsTo(User::class);
    }
}
