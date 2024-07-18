<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliquency;
use Illuminate\Http\Request;

class DeliquencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        // abort_if(Gate::denies('list user'), 403, 'You do not have the required authorization.');
        $data = Deliquency::with('committedBy')->latest();

        if ($request->search) {
            $data = $data->where('committed_by', 'LIKE', '%' . $request->search . '%');
        }
        // if ($request->filter == 'Deactivate') {
        //     $data = $data->where('deleted_at', null);
        // }
        // if ($request->filter == 'Activate') {
        //     $data = $data->whereNotNull('deleted_at');
        // }
        $data = $data->paginate($request->length);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Deliquency  $deliquency
     * @return \Illuminate\Http\Response
     */
    public function show(Deliquency $deliquency)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Deliquency  $deliquency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Deliquency $deliquency)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Deliquency  $deliquency
     * @return \Illuminate\Http\Response
     */
    public function destroy(Deliquency $deliquency)
    {
        //
    }
}
