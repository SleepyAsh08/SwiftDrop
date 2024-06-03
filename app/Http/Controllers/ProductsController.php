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

        $data = Products::OrderBy('Quantity', 'desc')
            ->latest();
        $data = $data->paginate($request->length);
        return response(['data' => $data], 200);
    }


    public function store(Request $request)
    {
        // dd($request->measurement_id);
        $request->validate([
            'product_name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
            'description' => 'required|string',
            'photos.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048' // validate each uploaded file
        ]);

        // Handle file uploads
        $photoPaths = [];
        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $photo) {
                $path = $photo->store('product_photos', 'public');
                $photoPaths[] = $path;
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
            'photos' => json_encode($photoPaths), // Store photo paths as JSON
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

    public function destroy($id)
    {
        $user = Products::findOrFail($id);
        $user->delete();
        return response(['message' => 'success'], 200);
    }
}
