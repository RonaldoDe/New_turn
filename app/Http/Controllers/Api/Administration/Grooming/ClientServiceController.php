<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use App\Models\Grooming\ClientService;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{
    public function __construct()
    {
        # List turn permission
        $this->middleware('permission:/list_services')->only(['clientServiceList']);
        # Get connection
        $this->middleware('set_connection');

    }
    public function clientServiceList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'state_id' => 'bail|integer',
            'employee_id' => 'bail|integer',
            'dni' => 'bail|max:15',
            'service_id' => 'bail|integer',
            'date_start' => 'bail|date_format:"Y-m-d"|date',
            'date_end' => 'bail|date_format:"Y-m-d"|date',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $services = ClientService::on('connectionDB')->get();

        return response()->json(['response' => $services], 200);
    }
}
