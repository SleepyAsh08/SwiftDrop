<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\AccountRegistration;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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
    protected $redirectTo = '/login';

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
            'lastname' => ['required', 'string'],
            'middle_initial' => ['nullable', 'string', 'max:2'],
            'date_of_birth' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if (Carbon::parse($value)->diffInYears(Carbon::now()) < 18) {
                        $fail('The date of birth indicates the user is under 18 years old.');
                    }
                },
            ],
            'contact_number' => ['required', 'string', 'digits:11'],
            'telephone_number' => ['nullable', 'string', 'digits:7'],
            'photos.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'], // validate each uploaded file
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
        $photos = request()->file('photos');
        $paths = [];

        if (!empty($photos)) {
            foreach ($photos as $photo) {
                $filename = time() . '.' . $photo->getClientOriginalExtension();
                $path = $photo->storeAs('Personal_Info_Photo', $filename, 'public');
                $paths[] = $path; // Add path to the array
            }
        }


        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            // 'password' => Hash::make($data['password']),
            'password' => $data['password'],
            'lastname' => $data['lastname'],
            'middle_initial' => $data['middle_initial'],
            'date_of_birth' => $data['date_of_birth'],
            'contact_number' => $data['contact_number'],
            'telephone_number' => $data['telephone_number'],
            'photos' => json_encode($paths), // Store photo paths as JSON
        ]);

        Mail::to($user->email)->send(new AccountRegistration($user));

        return $user;
    }
}
