<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'image',
        'title',
        'slug',
        'category_id',
        'description',
        'weight',
        'price',
        'discount'
    ];

    /**
     * getImageAttribute
     *
     * @param mixed $image
     * @return string
     */
    public function getImageAttribute($image)
    {
        return asset('storage/products/' . $image);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
