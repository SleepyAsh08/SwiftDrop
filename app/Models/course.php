<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class course extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function students()
    {
        return $this->hasMany(student::class);
    }
    public function getallcourse()
    {
        return course::all();
    }
    public function getLimitById($id)
    {
        return course::select('limit')->where('id', $id)->first();
    }
    public function getCourseById($id)
    {
        return course::where('id', $id)->get();
    }
}
