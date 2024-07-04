<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

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
        $data = User::with('roles', 'permissions')->latest();
        if ($request->search) {
            $data = $data->where('name', 'LIKE', '%' . $request->search . '%');
        }
        $data = $data->paginate($request->length);
        // dd($data);
        return response(['data' => $data], 200);
    }

    public function show()
    {
        abort_if(Gate::denies('access user'), 403, 'You do not have the required authorization.');
        $data = User::with('roles', 'permissions')->where('id', Auth::user()->id)->get();

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
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        $user->assignRole($request->role['name']);
        foreach ($request->permissions as $permission) {
            $user->givePermissionTo($permission['name']);
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
        } else {
            // dd($request->txtdesapproval);
            $user = User::findOrFail($id);

            $user->update([
                'reason_of_disapproval' => $request->txtdesapproval
            ]);
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
    public function update(Request $request, $id)
    {
        dd($request->all());
        $this->validate($request, [
            'name' => 'required|string|unique:users,name,' . $request->id,
            'email' => 'required|email|unique:users,email,' . $request->id,
            'password' => 'required|sometimes',
            'lastname' => 'required|string',
            'middle_initial' => 'required|string|max:2',
            'date_of_birth' => 'required|date',
            'contact_number' => 'required|string|digits:11',
            'telephone_number' => 'nullable|string|digits:7',
            'user_photo.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validate each uploaded file
        ]);

        $user = User::findOrFail($id);
        $user->update($request->except('user_photo'));

        if ($request->hasFile('user_photo')) {
            $photos = [];
            foreach ($request->file('user_photo') as $photo) {
                $path = $photo->store('photos', 'public');
                $photos[] = $path;
            }
            $user->user_photo = json_encode($photos);
        }


        $user->save();

        if ($request->password) {
            $user->password = Hash::make($request->password);
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
}
