<?php

namespace App\Services;

use App\Models\student;
use App\Actions\StudentAction;
use Illuminate\Support\Facades\DB;

class StudentService
{
    protected $StudentAction;

    public function __construct(StudentAction $StudentAction)
    {
        $this->StudentAction = $StudentAction;
    }

    public function createStudent($data, $request, $limit, $count)
    {
        if ($data[0]['min_GPA'] <= $request->GPA) {
            if ($limit['limit'] > $count) {
                if ($data[0]['is_scholar'] == true && $request->scholarship != null) {
                    return $this->StudentAction->CreateStudent($request);
                }
                if ($data[0]['is_scholar'] == false) {
                    return $this->StudentAction->CreateStudent($request);
                }
            } else {
                return response(['message' => 'The Course have reach the maximum limit of enrollee'], 400);
            }
        }
        return response(['message' => 'You are not qualified to take this course either you dont have scholarship or not meeting the minimum GPA'], 400);
    }
}
