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
            'price_per_hour' => 'bail|required|integer',
            'unit_per_hour' => 'bail|required|integer',
            'hours_max' => 'bail|integer',
            'wait_time' => 'bail|required|integer',
            'opening_hours' => 'bail|required',
            'state' => 'bail|required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }


        $service = Service::on('connectionDB')->create([
            'name' => request('name'),
            'description' => request('description'),
            'price_per_hour' => request('price_per_hour'),
            'unit_per_hour' => request('unit_per_hour'),
            'hours_max' => request('hours_max'),
            'wait_time' => request('wait_time'),
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
            'price_per_hour' => 'bail|required|integer',
            'unit_per_hour' => 'bail|required|integer',
            'hours_max' => 'bail|required|integer',
            'wait_time' => 'bail|required|integer',
            'opening_hours' => 'bail|required',
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
        $service->price_per_hour = request('price_per_hour');
        $service->unit_per_hour = request('unit_per_hour');
        $service->hours_max = request('hours_max');
        $service->wait_time = request('wait_time');
        $service->opening_hours = json_encode(request('opening_hours'));
        $service->state = request('state');
        $service->update();

        return response()->json(['response' => 'Success'], 200);
    }

}
