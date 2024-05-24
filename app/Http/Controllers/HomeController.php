<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\course;
use Illuminate\Http\Request;
use App\Services\StudentService;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    private student $StudentRepo;
    private course $CourseRepo;

    public function __construct(student $StudentRepo, course $CourseRepo)
    {
        $this->middleware('auth');

        $this->StudentRepo = $StudentRepo;
        $this->CourseRepo = $CourseRepo;
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
        $data = $this->CourseRepo->getallcourse();

        return response(['data' => $data], 200);
    }
    public function store_student(Request $request, StudentService $StudentService)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'scholarship' => 'string|nullable',
            'GPA' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'course_id.id' => 'required|integer',
        ]);

        //Model
        $count = $this->StudentRepo->countbyId($request->course_id['id']);
        $limit = $this->CourseRepo->getLimitById($request->course_id['id']);
        $data = $this->CourseRepo->getCourseById($request->course_id['id']);

        //services
        $student = $StudentService->createStudent($data, $request, $limit, $count);

        return $student;
    }

    public function store_course(Request $request)
    {
        $this->validate($request, [
            'course' => 'required|string',
            'limit' => 'required|integer',
            'min_GPA' => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'is_scholar' => 'nullable|in:1,0',
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
