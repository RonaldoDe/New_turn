<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
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
            'time' => 'bail|required|integer',
            'price' => 'bail|required|integer',
            'opening_hours' => 'bail|required',
            'pay_on_line' => 'bail|required|integer',
            'state' => 'bail|required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }


        $service = Service::on('connectionDB')->create([
            'name' => request('name'),
            'description' => request('description'),
            'time' => request('time'),
            'price' => request('price'),
            'pay_on_line' => request('pay_on_line'),
            'state' => request('state'),
            'opening_hours' => json_encode(request('opening_hours')),
        ]);

        return response()->json(['response' => 'Success'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $service = Service::on('connectionDB')->find($id);

        return response()->json(['response' => $service], 200);
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
        $validator=\Validator::make($request->all(),[
            'name' => 'required',
            'description' => 'required',
            'time' => 'bail|required|integer',
            'price' => 'bail|required|integer',
            'opening_hours' => 'bail|required',
            'pay_on_line' => 'bail|required|integer',
            'state' => 'bail|required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }


        $service = Service::on('connectionDB')->find($id);

        if(!$service){
            return response()->json(['response' => ['error' => ['El servicio no existe.']]], 400);
        }

        $service->name = request('name');
        $service->description = request('description');
        $service->time = request('time');
        $service->price = request('price');
        $service->opening_hours = json_encode(request('opening_hours'));
        $service->pay_on_line = request('pay_on_line');
        $service->state = request('state');
        $service->update();

        return response()->json(['response' => 'Success'], 200);
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
