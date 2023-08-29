<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'title',
        'stocks',
        'year',
        'cover_url'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, "book_categories", "book_id", "category_id")->withPivot('id');
        // return $this->belongsToMany(Regions::class, 'regions_stores', 'stores_id', 'regions_id');
    }
}
