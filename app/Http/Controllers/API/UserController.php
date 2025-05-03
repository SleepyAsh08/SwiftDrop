<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\ApprovalConfirmation;
use App\Mail\Disapproval;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('api');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        abort_if(Gate::denies('list user'), 403, 'You do not have the required authorization.');
        $data = User::withTrashed()->with('roles', 'permissions')->latest();
        if ($request->search) {
            $data = $data->where('name', 'LIKE', '%' . $request->search . '%');
        }
        if ($request->filter == 'Deactivate') {
            $data = $data->where('deleted_at', null);
        }
        if ($request->filter == 'Activate') {
            $data = $data->whereNotNull('deleted_at');
        }
        $data = $data->paginate($request->length);
        // dd($data);
        return response(['data' => $data], 200);
    }

    public function show()
    {

        abort_if(Gate::denies('edit user'), 403, 'You do not have the required authorization.');
        $data = User::with('roles', 'permissions')->where('id', Auth::user()->id)->get();

        // dd($data);
        return response(['data' => $data], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|unique:users,name,' . $request->id,
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'required|sometimes',
            'lastname' => 'required|string',
            'middle_initial' => 'nullable|string|max:2',
            'date_of_birth' => 'required|date',
            'contact_number' => 'required|string|digits:11',
            'telephone_number' => 'nullable|string|digits:7',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('Personal_Info_Photo', 'public');
                $photoPaths[] = $path;
            }
        }

        // dd($photoPaths);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            // 'password' => Hash::make($request->password),
            'password' => $request->password,
            'lastname' => $request->lastname,
            'middle_initial' => $request->middle_initial,
            'date_of_birth' => $request->date_of_birth,
            'contact_number' => $request->contact_number,
            'telephone_number' => $request->telephone_number,
            'photos' => json_encode($photoPaths),
        ]);

        if (isset($request->roles['name'])) {
            $user->assignRole($request->roles['name']);

            foreach ($request->permissions as $permission) {
                $user->givePermissionTo($permission['name']);
            }
        }
        return response(['message' => 'success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function validated(Request $request, $id)
    {
        //
        if ($request->selectedOption == 'approve') {
            $user = User::findOrFail($id);

            $user->update([
                'approved_at' => Carbon::now()->format('Y-m-d H:i:s')
            ]);

            Mail::to($user->email)->send(new ApprovalConfirmation($user));
        } else {
            // dd($request->txtdesapproval);
            $user = User::findOrFail($id);

            $user->update([
                'reason_of_disapproval' => $request->txtdesapproval
            ]);

            Mail::to($user->email)->send(new Disapproval($user));
        }

        return response(['message' => 'success'], 200);
    }
    public function disapprove($id)
    {
        //
        $user = User::findOrFail($id);

        $user->update([
            'approved_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return response(['message' => 'success'], 200);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

        // dd($request->all())

        $this->validate($request, [
            'name' => 'required|string|unique:users,name,' . $request->id,
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'required|sometimes',
            'lastname' => 'required|string',
            'date_of_birth' => 'required|date',
            'contact_number' => 'required|string|digits:11',
            // 'user_photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validate each uploaded file
            // 'user_photo.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ]);

        // dd($request->hasFile('user_photo'));
        $photoPaths = [];
        if ($request->hasFile('user_photo')) {
            foreach ($request->file('user_photo') as $photo) {
                $path = $photo->store('user_photo', 'public');
                $photoPaths[] = $path;
            }
        }

        $middle_initial = $request->middle_initial == "null" ? " " :$request->middle_initial;
        // dd($middle_initial);

        $user = User::findOrFail($request->id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'lastname' => $request->lastname,
            'middle_initial' => $middle_initial,
            'date_of_birth' => $request->date_of_birth,
            'contact_number' => $request->contact_number,
            'telephone_number' => $request->telephone_number,
            // 'photos' => $request->photos,
            'user_photo' => json_encode($photoPaths)
        ]);

        if (!empty($photoPaths)) {
            $user['user_photo'] = json_encode($photoPaths);
        }

        if ($request->password) {
            // $user->password = Hash::make($request->password);
            $user->password  = $request->password;
            $user->save();
        }

        if (isset($request->roles['name'])) {
            $user->roles()->detach();
            $user->assignRole($request->roles['name']);

            foreach ($user->permissions as $permission) {
                $user->revokePermissionTo($permission['name']);
            }
            foreach ($request->permissions as $permission) {
                $user->givePermissionTo($permission['name']);
            }
        }

        return response(['message' => 'success'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response(['message' => 'success'], 200);
    }
    public function activate($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();
        return response(['message' => 'success'], 200);
    }

    public function assign(Request $request)
    {
        // dd(json_encode($photoPaths));

        $user = User::findOrFail($request->id);

        if ($request->password) {
            // $user->password = Hash::make($request->password);
            $user->password  = $request->password;
            $user->save();
        }

        if (isset($request->roles['name'])) {
            $user->roles()->detach();
            $user->assignRole($request->roles['name']);

            foreach ($user->permissions as $permission) {
                $user->revokePermissionTo($permission['name']);
            }
            foreach ($request->permissions as $permission) {
                $user->givePermissionTo($permission['name']);
            }
        }

        return response(['message' => 'success'], 200);
    }
}
