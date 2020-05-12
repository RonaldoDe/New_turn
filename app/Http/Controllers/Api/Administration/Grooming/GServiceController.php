<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use App\Models\Grooming\Service;
use Illuminate\Http\Request;

class GServiceController extends Controller
{

    public function __construct()
    {
        # List services config permission
        $this->middleware('permission:/list_c_service')->only(['index', 'show']);
        # Modify services config permission
        $this->middleware('permission:/create_c_service')->only(['store', 'update', 'destroy']);
        # Get connection
        $this->middleware('set_connection');

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $service = Service::on('connectionDB')->get();

        return response()->json(['response' => $service], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'price_per_hour' => 'bail|integer',
            'unit_per_hour' => 'bail|integer',
            'hours_max' => 'bail|required|integer',
            'wait_time' => 'bail|required|integer',
            'opening_hours' => 'bail|required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }


        $service = Service::create([
            'name' => request('name'),
            'description' => request('description'),
            'price_per_hour' => request('price_per_hour'),
            'unit_per_hour' => request('unit_per_hour'),
            'hours_max' => request('hours_max'),
            'wait_time' => request('wait_time'),
            'opening_hours' => request('opening_hours'),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
