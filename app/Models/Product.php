<?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;

// class Product extends Model
// {
//     use HasFactory;
//     protected $fillable = [
//         'name',
//         'brand',
//         'model',
//         'capacity',
//         'power',
//         'voltage',
//         'color',
//         'weight',
//         'dimensions',
//         'functions',
//         'warranty',
//         'price',
//         'stock',
//         'image',
//         'category_id'
//     ];

//     public function category()
//     {
//         return $this->belongsTo(Category::class);
//     }
// }

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Cho phép gán hàng loạt các trường này
    protected $fillable = [
        'name',
        'brand',
        'model',
        'capacity',
        'power',
        'voltage',
        'color',
        'weight',
        'dimensions',
        'functions',
        'warranty',
        'price',
        'stock',
        'image',
        'category_id',
    ];

    // Quan hệ với Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Quan hệ với OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'product_id');
    }
    // Quan hệ với Review
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
}
