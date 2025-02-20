<?php

namespace App\Http\Controllers;

use App\Models\Measurement;
use App\Models\Products;
use App\Models\User;
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
        $data = Products::where('userID', $userID)
            ->latest();

        $data = $data->paginate($request->length);
        // dd($data);
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }


    public function index_all()
    {
        // $user = User::all();

        $data = Products::with('User')->get();

        // dd($data);

        $formattedData = $data->map(function ($product) {
            return [
                'id' => $product->id,
                'Item_Name' => $product->Item_Name,
                'Item_Barcode' => $product->Item_Barcode,
                'userID' => $product->userID,
                'first_name' => $product->user->name ?? null,
                'last_name' => $product->user->lastname ?? null, // Add user name here
                'contact_number' => $product->user->contact_number ?? null, // Add user name here
            ];
        });

        // dd($formattedData);
        return response()->json(['data' => $formattedData], 200);
    }

    public function product($id){
         // Fetch the product by ID with the associated User
    $product = Products::with('User')->find($id);

    // Check if product exists
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    // Format the data
    $formattedData = [
        'id' => $product->id,
        'Product_Name' => $product->Product_Name,
        'idCategory' => $product->idCategory,
        'price' => $product->price,
        'idMeasurement' => $product->idMeasurement,
        'Quantity' => $product->Quantity,
        'Description' => $product->Description,
        'created_at' => $product->created_at,
        'updated_at' => $product->updated_at,
        'photos' => json_decode($product->photos),
        'photos1' => json_decode($product->photos1),
        'photos2' => json_decode($product->photos2),
        'userID' => $product->userID,
        'first_name' => $product->user->name ?? null,
        'last_name' => $product->user->lastname ?? null,
        'contact_number' => $product->user->contact_number ?? null,
    ];

    return response()->json(['data' => $formattedData], 200);

    }

    public function index1(Request $request)
    {
        $userID = auth()->user()->id;

        // dd($emp_code);
        $data = Products::with('Category', 'Measurement')
            ->OrderBy('Quantity', 'desc')
            ->where('userID', $userID)
            ->where('Quantity', '<=', 10)
            ->latest();

        $data = $data->paginate($request->length);

        $data->getCollection()->transform(function ($item) {
            // Add the 'category' field from the related Category model to the product data
            $item->category_name = $item->category ? $item->category->category : null; // 'category' is the field in the Category model

            $item->measurement_name = $item->measurement ? $item->measurement->measurement : null;
            return $item;
        });
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }


    public function store(Request $request)
    {
        // dd($request->all());
        $userID = auth()->user()->id;
        // dd($request->all());
        // dd($request->measurement_id);
        $request->validate([
            'Item_Name' => 'required|string|max:255',
        ]);


        $product = Products::create([
            'Item_Name' => $request->Item_Name,
            'Item_Barcode' => $request->Item_Barcode,
            'userID' => $userID,
            // Store photo paths as JSON
        ]);

        // dd($product);
        return response(['message' => 'success'], 200);
    }

    public function update(Request $request, $id)
    {

        // dd($request->measurement_id);
        // dd($request->measurement_id);

        $measurement = $request->measurement_id['id'];

        // dd($measurement);
        $this->validate($request, []);
        $user = Products::findOrFail($id);

        $user->update([
            'Product_Name' => $request->Product_Name,
            'price' => $request->price,
            'Quantity' => $request->Quantity,
            'Description' => $request->Description,
            'idMeasurement' => $measurement,
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
