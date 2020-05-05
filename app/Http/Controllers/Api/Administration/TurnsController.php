<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\ClientTurn;
use App\Models\CUser;
use App\User;
use Illuminate\Http\Request;

class TurnsController extends Controller
{
    public function __construct()
    {
        # List turn permission
        $this->middleware('permission:/list_turns')->only(['turnsList']);
        # Get connection
        $this->middleware('set_connection');

    }

    public function turnsList(Request $request)
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
        $turns_list = ClientTurn::on('connectionDB')
        ->state(request('state_id'))
        ->employee(request('employee_id'))
        ->dni(request('dni'))
        ->service(request('service_id'))
        ->range(request('date_start'), request('date_end'))
        ->get();
        $data = array(
            'employee' => 0,
            'client' => 0,
            'started_by' => 0,
            'finished_by' => 0,
        );

        foreach ($turns_list as $turn) {
            if($turn->employee_id != null){
                $data['employee'] = CUser::where('id', $turn->employee_id)->first();
                /*if(!$employee){
                    return response()->json(['response' => ['error' => ['Empleado no encontrado.']]], 400);
                }*/
            }

            $client_p = User::find($turn->user_id)->first();
            if($client_p->phanton_user){
                $data['client'] = CUser::on('connectionDB')->where('principal_id', $client_p->id)->first();
            }
            if($turn->started_by != null){
                $data['started_by'] = CUser::on('connectionDB')->where('id', $turn->started_by)->first();
            }
            if($turn->finished_by_id != null){
                $data['finished_by'] = CUser::on('connectionDB')->where('id', $turn->finished_by_id)->first();
            }

            $turn->employee_asigned = $data['employee'];
            $turn->client = $data['client'];
            $turn->started_by = $data['started_by'];
            $turn->finished_by = $data['finished_by'];

        }

        return response()->json(['response' => $turns_list], 200);
    }
}
