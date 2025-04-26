<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\User;
use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    private $model;
    public function __construct(Measurement $model)
    {
        $this->model = $model;
    }
    public function index(Request $request)
    {
        // dd('sample');
        // dd("");
        $data = Measurement::latest();
        $data = $data->paginate($request->length);
        return response(['data' => $data], 200);
    }
    public function index_all()
    {
        $data = User::join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
                    ->where('roles.name', 'courier')
                    ->select('users.*') // or add specific fields you want
                    ->get();
        return response(['data' => $data], 200);
    }
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'name' => 'required|string',
        //     'email' => 'required|email',
        //     'password' => 'required|string',
        // ]);
        // $data = $this->model->create($request->all());
        $data = Measurement::create([
            'measurement' => $request->measurement,

        ]);
        // $data->assignRole($request->role['name']);
        // foreach ($request->permissions as $permission) {
        //     $data->givePermissionTo($permission['name']);
        // }
        return response(['message' => 'success'], 200);
    }
    public function update(Request $request, $id)
    {


        $this->validate($request, []);
        $user = Measurement::findOrFail($id);
        $user->update([
            'measurement' => $request->measurement,
        ]);

        return response(['message' => 'success'], 200);
    }

    public function destroy($id)
    {
        $user = Measurement::findOrFail($id);
        $user->delete();
        return response(['message' => 'success'], 200);
    }
}
