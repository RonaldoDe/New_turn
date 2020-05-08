<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\ClientTurn;
use App\Models\CUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TurnsController extends Controller
{
    public function __construct()
    {
        # List turn permission
        $this->middleware('permission:/list_turns')->only(['turnsList', 'changeTurn']);
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
            $turn->started_by_data = $data['started_by'];
            $turn->finished_by = $data['finished_by'];

        }

        return response()->json(['response' => $turns_list], 200);
    }

    public function changeTurn(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'state_id' => 'bail|integer',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        $turn = ClientTurn::on('connectionDB')->find($id);

        if(!$turn){
            return response()->json(['response' => ['error' => ['Turno no encontrado.']]], 400);
        }

        $user = CUser::on('connectionDB')->where('principal_id', Auth::id())->first();

        if(!$user){
            return response()->json(['response' => ['error' => ['Usuario no encontrado.']]], 400);
        }

        $turn_state = DB::connection('connectionDB')->table('turn_state')->where('id', request('state_id'))->first();

        if(!$turn_state){
            return response()->json(['response' => ['error' => ['Estado de el turno no encontrado.']]], 400);
        }

        if(request('state_id') == 1){
            if($turn->state_id == 4){
                return response()->json(['response' => ['error' => ['El turno ya se encuentra finalizado.']]], 400);
            }else if($turn->state_id == 3){
                return response()->json(['response' => ['error' => ['El turno se encuentra cancelado.']]], 400);
            }else if($turn->state_id == 1){
                return response()->json(['response' => ['error' => ['El turno ya se encuentra iniciado.']]], 400);
            }

            $turn->start_at = date('Y-m-d H:i:s');
            $turn->started_by = $user->id;
            $turn->state_id = 1;
            $turn->update();

            return response()->json(['response' => 'Turno iniciado.'], 200);

        }else if(request('state_id') == 3){
            if($turn->state_id == 3){
                return response()->json(['response' => ['error' => ['El turno se encuentra cancelado.']]], 400);
            }else if($turn->state_id == 4){
                return response()->json(['response' => ['error' => ['El turno se encuentra terminado.']]], 400);
            }

            $turn->state_id = 3;
            $turn->update();
            return response()->json(['response' => 'Turno cancelado.'], 200);

        }else if(request('state_id') == 4){
            if($turn->state_id == 4){
                return response()->json(['response' => ['error' => ['El turno ya se encuentra finalizado.']]], 400);
            }else if($turn->state_id == 3){
                return response()->json(['response' => ['error' => ['El turno se encuentra cancelado.']]], 400);
            }else if($turn->state_id == 2){
                return response()->json(['response' => ['error' => ['El turno aun no se empieza.']]], 400);
            }

            $turn->finished_at = date('Y-m-d H:i:s');
            $turn->finished_by_id = $user->id;
            $turn->state_id = 4;
            $turn->update();

            return response()->json(['response' => 'Turno finalizado.'], 200);
        }else if(request('state_id') == 2){
            return response()->json(['response' => ['error' => ['El turno no se puede poner nuevamente en espera.']]], 400);
        }

    }

}
