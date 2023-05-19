<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
    public function all_courses()
    {
        $data = course::select('id', 'course')->get();

        return response(['data' => $data], 200);
    }
    public function store_student(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'scholarship' => 'string|nullable',
            'GPA' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'course_id.id' => 'required|integer',
        ]);
        $data = course::where('id', $request->course_id['id'])->get();
        if ($data[0]['min_GPA'] <= $request->GPA) {
            if ($data[0]['is_scholar'] == true && $request->scholarship != null) {
                student::create([
                    'name' => $request->name,
                    'scholarship' => $request->scholarship,
                    'GPA' => $request->GPA,
                    'course_id' => $request->course_id['id'],
                ]);
                return response(['message' => 'success'], 200);
            }
            if ($data[0]['is_scholar'] == false) {
                student::create([
                    'name' => $request->name,
                    'scholarship' => $request->scholarship,
                    'GPA' => $request->GPA,
                    'course_id' => $request->course_id['id'],
                ]);
                return response(['message' => 'success'], 200);
            }
        }
        return response(['message' => 'You are not qualified to take this course either you dont have scholarship or not meeting the minimum GPA'], 400);
    }

    public function store_course(Request $request)
    {
        $this->validate($request, [
            'course' => 'required|string',
            'limit' => 'required|integer',
            'min_GPA' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'is_scholar' => 'required|in:1,0',
        ]);

        course::create([
            'course' => $request->course,
            'limit' => $request->limit,
            'min_GPA' => $request->min_GPA,
            'is_scholar' => $request->is_scholar,
        ]);
        return response(['message' => 'success'], 200);
    }
}
