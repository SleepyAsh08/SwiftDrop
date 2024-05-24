<?php

namespace App\Actions;

use App\Models\student;
use Illuminate\Support\Facades\DB;

class StudentAction
{
    public function CreateStudent($request)
    {
        student::create([
            'name' => $request->name,
            'scholarship' => $request->scholarship,
            'GPA' => $request->GPA,
            'course_id' => $request->course_id['id'],
        ]);

        return response(['message' => 'success'], 200);
    }
}
