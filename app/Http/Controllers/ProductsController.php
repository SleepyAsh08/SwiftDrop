<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ProductsController extends Controller
{
    //
    private $model;
    public function __construct(Products $model)
    {
        $this->model = $model;
    }

    public function index(Request $request)
    {
        $userID = auth()->user()->id;

        // dd($emp_code);
        $data = Products::OrderBy('Quantity', 'desc')
            ->where('userID', $userID)
            ->where('Quantity', '>', 10)
            ->latest();
        $data = $data->paginate($request->length);
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }

    public function index1(Request $request)
    {
        $userID = auth()->user()->id;

        // dd($emp_code);
        $data = Products::OrderBy('Quantity', 'desc')
            ->where('userID', $userID)
            ->where('Quantity', '<=', 10)
            ->latest();

        // dd($data);
        $data = $data->paginate($request->length);
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }


    public function store(Request $request)
    {
        $userID = auth()->user()->id;
        // dd();
        // dd($request->measurement_id);
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validate each uploaded file
            'photos1.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validate each uploaded file
            'photos2.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // validate each uploaded file
        ]);

        // Handle file uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $photoPaths[] = $path;
            }
        }

        $photoPaths1 = [];
        if ($request->hasFile('photos1')) {
            foreach ($request->file('photos1') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $photoPaths1[] = $path;
            }
        }

        $photoPaths2 = [];
        if ($request->hasFile('photos2')) {
            foreach ($request->file('photos2') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $photoPaths2[] = $path;
            }
        }
        // dd($photoPaths);
        // Create product with photo paths
        $product = Products::create([
            'Product_Name' => $request->product_name,
            'price' => $request->price,
            'Quantity' => $request->quantity,
            'Description' => $request->description,
            // 'idMeasurement' => $request->measurement_id,
            'photos' => json_encode($photoPaths),
            'photos1' => json_encode($photoPaths1),
            'photos2' => json_encode($photoPaths2),
            'userID' => $userID,
            // Store photo paths as JSON
        ]);

        // Dump a message indicating product creation with photos


        return response(['message' => 'success'], 200);
    }

    public function update(Request $request, $id)
    {

        // dd($request->measurement_id);


        $this->validate($request, []);
        $user = Products::findOrFail($id);
        $user->update([
            'Product_Name' => $request->Product_Name,
            'price' => $request->price,
            'Quantity' => $request->Quantity,
            'Description' => $request->Description,
            // 'idMeasurement' => $request->measurement_id,
        ]);

        return response(['message' => 'success'], 200);
    }

    public function update1(Request $request, $id)
    {

        // dd($request->measurement_id);


        $this->validate($request, []);
        $user = Products::findOrFail($id);
        // dd($user);
        $user->update([
            'Product_Name' => $request->Product_Name,
            'price' => $request->price,
            'Quantity' => $request->Quantity,
            'Description' => $request->Description,
            // 'idMeasurement' => $request->measurement_id,
        ]);

        return response(['message' => 'success'], 200);
    }

    public function destroy($id)
    {
        $user = Products::findOrFail($id);
        $user->delete();
        return response(['message' => 'success'], 200);
    }
}
