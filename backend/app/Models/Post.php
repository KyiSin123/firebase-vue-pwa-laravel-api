<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'image',
    ];

    public function categories() {

        return $this->belongsToMany(Category::class, 'category_posts');
    }

    public function scopeSort($query)
    {
        return $query->orderBy('id', 'desc');
    }

    public function scopeFilter($query, $search)
    {
        return $query->when($search ?? false, function ($query, $search) {
            $query->where('title', 'like', '%' . $search . '%')
                ->orWhereHas('categories', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        });
    }

    public function user() {

        return $this->belongsTo(User::class);
    }
}
