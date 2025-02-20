<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;
    protected $fillable = [
        'Item_Name',
        'Item_Barcode',
        'userID',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }
}
