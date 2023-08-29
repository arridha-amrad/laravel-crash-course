<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public $timestamps = false;

    public function books()
    {
        return $this->belongsToMany(Book::class, 'book_categories', 'category_id', 'book_id')->withPivot('id');
        // return $this->belongsToMany(Regions::class, 'regions_stores', 'stores_id', 'regions_id');

    }
}
