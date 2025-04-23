<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowRecord extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'book_id','staff_id', 'borrow_date', 'return_date', 'status'];

    // Relationship with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with the Book model
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
