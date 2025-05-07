<?php

namespace App\Http\Controllers;

use App\Mail\ForP;
use App\Mail\ForPickUp;
use App\Mail\PickedUp;
use App\Models\Measurement;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
        $data = Products::select(
            'products.*',
            DB::raw("CONCAT(users.name, ' ', users.lastname) as courier_name")
        )
        ->leftJoin('users', 'products.idCourier', '=', 'users.id')
        ->where('products.userID', $userID)
        ->where('products.status', 'For Pick Up')
        ->latest();
        // $data = Products::where('userID', $userID)
        //     ->latest();

        $data = $data->paginate($request->length);
        // dd($data);
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }

    public function indexs(Request $request)
    {
        $userID = auth()->user()->id;

        // dd($emp_code);
        $data = Products::select(
            'products.*',
            DB::raw("CONCAT(users.name, ' ', users.lastname) as courier_name")
        )
        ->leftJoin('users', 'products.idCourier', '=', 'users.id')
        ->where('products.userID', $userID)
        ->where('products.status', 'Picked Up')
        ->latest();
        // $data = Products::where('userID', $userID)
        //     ->latest();

        $data = $data->paginate($request->length);
        // dd($data);
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }
    public function index_user(Request $request)
    {
        $userID = auth()->user()->id;

        // dd($emp_code);
        $data = Products::select(
            'products.*',
            DB::raw("CONCAT(users.name, ' ', users.lastname) as seller_name")
        )
        ->leftJoin('users', 'products.userID', '=', 'users.id')
        ->where('products.idCourier', $userID)
        ->where('products.status', 'For Pick Up')
        ->latest();
        // $data = Products::where('userID', $userID)
        //     ->latest();

        $data = $data->paginate($request->length);
        // dd($data);
        return response([
            'data' => $data,
            'userID' => $userID,
        ], 200);
    }

    public function index_users(Request $request)
    {
        $userID = auth()->user()->id;

        // dd($emp_code);
        $data = Products::select(
            'products.*',
            DB::raw("CONCAT(users.name, ' ', users.lastname) as seller_name")
        )
        ->leftJoin('users', 'products.userID', '=', 'users.id')
        ->where('products.idCourier', $userID)
        ->where('products.status', 'Picked Up')
        ->latest();
        // $data = Products::where('userID', $userID)
        //     ->latest();

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

        $data = Products::with('User')->where('status', 'For Pick Up')
        ->get();

        // dd($data);

        $formattedData = $data->map(function ($product) {
            return [
                'id' => $product->id,
                'Item_Name' => $product->Item_Name,
                'Item_Barcode' => $product->Item_Barcode,
                'userID' => $product->userID,
                'status' =>$product->status,
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
        $request->validate([
            'Item_Name' => 'required|string|max:255',
        ]);


        // dd($request->Item_Barcode);

        $existingProduct = Products::where('Item_Barcode', $request->Item_Barcode)->first();


        // dd($existingProduct);

        if ($existingProduct) {
            return response([
                'message' => 'Item_Barcode already exists.'
            ], 409); // 409 Conflict
        }

        // dd($existingProduct);
        $Courier = User::where('id', $request->idCourier)
        ->first();
        // dd($Courier);
        $status = 'For Pick Up';

        $product = Products::create([
            'Item_Name' => $request->Item_Name,
            'Item_Barcode' => $request->Item_Barcode,
            'userID' => $userID,
            'idCourier' => $request->idCourier,
            'status' => $status,
        ]);


        Mail::to($Courier->email)->send(new ForPickUp($Courier));

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

    public function updateDelivery(Request $request, $id)
    {

        // dd($request->all());
        $user = Products::findOrFail($id);

        // dd($user->userID);

        $seller= User::where('id', $user->userID)->first();

        // dd($seller);

        $status = "Picked Up";
        $user->update([
            'status' => $status,
        ]);

        Mail::to($seller->email)->send(new PickedUp($seller));

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
