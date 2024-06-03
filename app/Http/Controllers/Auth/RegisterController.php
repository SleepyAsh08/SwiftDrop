<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'photos' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048' // validate each uploaded file
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        // dd($photo = $data['photos']->file('photo'));
        // Handle file uploads
        // $filename = [];
        $photos = $data['photos'];

        if (!empty($photos)) {
            $path = [];
            foreach ($photos as $photo) {
                $filename = time() . '.' . $photo->getClientOriginalExtension();
                $path = Storage::disk('public')->put('Personal_Info_Photo', $photo, $filename);
                // $paths[] = $path; // Add path to the array

            }

            // Handle file uploads
            // $photoPaths = [];
            // if ($request->hasFile('photos')) {
            //     foreach ($request->file('photos') as $photo) {
            //         $path = $photo->store('product_photos', 'public');
            //         $photoPaths[] = $path;
            //     }
            // }
            dd($photos);
            // return User::create([
            //     'name' => $data['name'],
            //     'email' => $data['email'],
            //     'password' => $data['password'],
            //     'photos' => json_encode($paths), // Store photo paths as JSON
            // ]);
        }
    }
}
