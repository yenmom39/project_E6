<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Author;
use App\Models\Category;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'author_id',
        'category_id',
        'isbn',
        'published_year',
        'total_copies',
        'available_copies',
    ];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}