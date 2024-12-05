<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'Product_Name',
        'idCategory',
        'price',
        'Quantity',
        'Description',
        'idMeasurement',
        'photos',
        'photos1',
        'photos2',
        'userID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'idCategory'); // Make sure the foreign key is correct
    }

    public function measurement(){
        return $this->belongsTo(Measurement::class, 'idMeasurement');
    }
}
