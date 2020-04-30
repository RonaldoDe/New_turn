<?php

namespace App\Http\Controllers\Api\Turns;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\PayUHelper;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\ClientTurn;
use App\Models\CompanyData;
use App\Models\Master\BranchOffice;
use App\Models\Master\TransactionLog;
use App\Models\PaymentData;
use App\Models\Service;
use App\Models\UserTurn;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TurnsClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_turn = UserTurn::select('c.name as company_name', 'c.description as company_description', 'bo.id as branch_id', 'bo.name as branch_name', 'bo.description as branch_description', 'ct.name as company_type', 'user_turn.service_type as type_turn', 'user_turn.created_at')
        ->join('branch_office as bo', 'user_turn.branch_id', 'bo.id')
        ->join('company as c', 'bo.company_id', 'c.id')
        ->join('company_type as ct', 'c.type_id', 'ct.id')
        ->where('user_turn.user_id', Auth::id())
        ->get();

        return response()->json(['response' => $user_turn], 400);
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
            'branch_id' => 'required|integer|exists:branch_office,id',
            'service_id' => 'required|integer',
            'pay_on_line' => 'bail|integer|required',
            'payment_data_id' => 'bail|integer|exist:payment_data,id',
            'credit_card_number' => 'bail|integer',
            'credit_card_expiration_date' => 'bail|integer',
            'credit_card_security_code' => 'bail|integer',
            'employee_id' => 'bail|integer',
            'dni' => 'bail|',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $user = User::find(Auth::id());

        if($user->phanton_user){
            if(request('dni') != ''){
                $dni = request('dni');
            }else{
                return response()->json(['response' => ['error' => ['Insertar Dni']]], 400);
            }
        }else{
            $dni = $user->dni;
        }

        # --------------------- Set connection ------------------------------------#
        $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        $company_data = CompanyData::on($branch->db_name)->find(1);
        if($branch->hours_24){
            $turn_number = ClientTurn::on($branch->db_name)->where('c_return', $company_data->current_return)->max('turn_number');
            if($company_data->turns_number == $turn_number){
                $company_data->current_return = $company_data->current_return + 1;
                $turn_number = 0;
            }
        }else{
            $turn_number = ClientTurn::on($branch->db_name)->where('today', date('Y-m-d'))->max('turn_number');
        }

        DB::beginTransaction();
        DB::connection($branch->db_name)->beginTransaction();
        try{

            $turn_client = UserTurn::create([
                'user_id' => $user->id,
                'branch_id' => $branch->id,
                'service_type' => 'barber_turn',
            ]);

            if(request('pay_on_line')){
                if($company_data->pay_on_line){
                    $payment_data = PaymentData::where('user_id', Auth::id())->where('id', request('payment_data_id'))->first();
                    if(!$payment_data){
                        return response()->json(['response' => ['error' => ['Datos de la tarjeta de credito no encontrados']]],400);
                    }

                    $account_config = array(
                        'api_k' => $company_data->api_k,
                        'api_l' => $company_data->api_l,
                        'mer_id' => $company_data->mer_id,
                        'acc_id' => $company_data->acc_id
                    );

                    $service_to_pay = Service::on($branch->db_name)->find(request('service_id'));
                    $payU = PayUHelper::paymentCredit($account_config, json_decode($payment_data->configuration), $user, request('credit_card_number'), request('credit_card_expiration_date'), request('credit_card_security_code'), $service_to_pay->price);
                    $log = TransactionLog::create([
                        'user_id' => $user->id,
                        'payment_id' => $payment_data->id,
                        'branch_id' => $branch->id,
                        'service_id' => $service_to_pay->id,
                        'action_id' => 'Barber'
                    ]);
                }
            }
            $turn = ClientTurn::on($branch->db_name)->create([
                'employee_id' => request('employee_id'),
                'user_id' => $user->id,
                'user_turn_id' => $turn_client->id,
                'dni' => $dni,
                'service_id' => request('service_id'),
                'today' => date('Y-m-d'),
                'turn_number' => $turn_number+1,
                'c_return' => $company_data->current_return,
                'state_id' => 2,
            ]);
            }catch(Exception $e){
                DB::rollback();
                DB::connection($branch->db_name)->rollback();
                return response()->json(['response' => ['error' => [$e->getMessage()]]],400);
            }

        DB::commit();
        DB::connection($branch->db_name)->commit();
        return response()->json(['response' => 'Turno reservado con exito', 'turn' => $turn->id], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user_turn = UserTurn::select('user_turn.id', 'u.id as user_id', 'bo.id as compnay_id', 'u.name as user_name', 'bo.name as company_name', 'bo.db_name')
        ->join('users as u', 'user_turn.user_id', 'u.id')
        ->join('branch_office as bo', 'user_turn.branch_id', 'bo.id')
        ->where('user_turn.user_id', Auth::id())
        ->find($id);

        if(!$user_turn){
            return response()->json(['response' => ['error' => ['Turno no encontrado.']]], 400);
        }

        # --------------------- Set connection ------------------------------------#
        $set_connection = SetConnectionHelper::setByDBName($user_turn->db_name);
        # --------------------- Set connection ------------------------------------#

        $client_turn = ClientTurn::on($user_turn->db_name)->where('user_id', $user_turn->user_id)->where('user_turn_id', $user_turn->id)->whereIn('state_id', [2, 4, 1])->first();
        $current_turn = ClientTurn::on($user_turn->db_name)->where('state_id', 1)->max('turn_number');
        $service = Service::on($user_turn->db_name)->find($client_turn->service_id);
        $client_turn->current_turn = $current_turn;
        $client_turn->service_name = $service->name;
        $client_turn->service_description = $service->description;
        $client_turn->service_time = $service->time;

        return response()->json(['response' => $client_turn], 200);

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
