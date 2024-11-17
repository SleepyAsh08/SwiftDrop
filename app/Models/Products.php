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
}
