<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelpersData;
use App\Http\Controllers\Helper\PayUHelper;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CUser;
use App\Models\Grooming\ClientService;
use App\Models\Master\BranchOffice;
use App\Models\Grooming\GCompanyData;
use App\Models\Master\TransactionLog;
use App\Models\PaymentData;
use App\Models\Grooming\Service;
use App\Models\UserTurn;
use Illuminate\Http\Request;
use App\User;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RequestServiceController extends Controller
{
    public function requestService(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'required|integer|exists:branch_office,id',
            'service_id' => 'required|integer',
            'employee_id' => 'bail|integer|required',
            #'total_minutes' => 'bail|required|integer',
            'date_start' => 'bail|required|date_format:"Y-m-d H:i:s"|date',
            # 'date_end' => 'bail|required|date_format:"Y-m-d H:i:s"|date',
            'pay_on_line' => 'bail|integer|required',
            'payment_data_id' => 'bail|integer',
            'credit_card_number' => 'bail|integer',
            'credit_card_expiration_date' => 'bail',
            'credit_card_security_code' => 'bail|integer',
            'agent' => 'bail',
            'device' => 'bail',
            'cookie' => 'bail',
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
        $branch = BranchOffice::select('branch_office.id', 'branch_office.db_name')
        ->join('company as c', 'branch_office.company_id', 'c.id')
        ->where('branch_office.id', '!=', 1)
        ->where('c.type_id', 3)
        ->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada o no es de aseo']]], 400);
        }

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        $company_data = GCompanyData::on($branch->db_name)->find(1);

        $service = Service::on($branch->db_name)->find(request('service_id'));

        if(!$service){
            return response()->json(['response' => ['error' => ['Servicio no encontrado']]], 400);
        }

        $date_end = date('Y-m-d H:i:s', strtotime('+'.$service->unit_per_hour.' minute', strtotime(date(request('date_start')))));


        # Validate opening hours
        $validate_day = HelpersData::validateDay(request('date_start'), $date_end, $service);

        if($validate_day != 1){
            return response()->json(['response' => ['error' => $validate_day]], 400);
        }


        DB::beginTransaction();
        DB::connection($branch->db_name)->beginTransaction();
        try{


            /*if(request('date_end') <= request('date_start')){
                return response()->json(['response' => ['error' => ['La fecha de inicio debe ser menor que la fecha final.']]], 400);
            }*/

            $current_date_more_waiting = date('Y-m-d H:i:s', strtotime('+'.$service->wait_time.' minute', strtotime(date('Y-m-d H:i:s'))));

            if(request('date_start') < $current_date_more_waiting){
                return response()->json(['response' => ['error' => ['La fecha de inicio debe ser mayor que la fecha actual MAS '.$service->wait_time.' minutos de la llegada de el empleado..']]], 400);
            }

            # unit of measurement
            /*
            $i = 0;
            # Number of unit per hours to pay
            $count = 0;
            # Not is a good relation
            $relation = 0;

            while(TRUE){
                $count++;
                $i += $service->unit_per_hour;
                if($i == request('total_minutes')){
                    $relation = 1;
                    break;

                }

                if($i > request('total_minutes')){
                    $relation = 0;
                    break;
                }

            }

            if(!$relation){
                return response()->json(['response' => ['error' => ['La relación entre los minutos y el minimo tiempo de el servicio no coinciden.']]], 400);
            }*/

            $date_start = new DateTime(request('date_start'));

            /*$date_end = new DateTime(request('date_end'));
            $diff = $date_start->diff($date_end);

            $hours = $diff->h;
            $minutes = $diff->i;

            $total_diff = ($hours * 60) + $minutes;

            if($total_diff != request('total_minutes')){
                return response()->json(['response' => ['error' => ['El total de minutos y el rango de fechas no coinciden.']]], 400);
            }*/

            /*$suggested_employee = null;
            if(!empty(request('employee_id'))){
                $suggested_employee = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')
                ->join('user_has_role as ur', 'users.id', 'ur.user_id')
                ->join('client_service as cs', 'users.id', 'cs.employee_id')
                ->where('ur.role_id', 2)
                ->where('cs.employee_id', request('employee_id'))
                ->whereIn('cs.state_id', [1, 3, 4, 6])
                ->first();
            }else{
                if(!$suggested_employee){
                    $suggested_employee = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')
                    ->join('user_has_role as ur', 'users.id', 'ur.user_id')
                    ->join('client_service as cs', 'users.id', 'cs.employee_id')
                    ->where('ur.role_id', 2)
                    ->whereIn('cs.state_id', [1, 3, 4, 6])
                    ->first();

                    if(!$suggested_employee){
                        $last_employee = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name', 'cs.date_end')
                        ->join('user_has_role as ur', 'users.id', 'ur.user_id')
                        ->join('client_service as cs', 'users.id', 'cs.employee_id')
                        ->where('ur.role_id', 2)
                        ->whereIn('cs.state_id', [2, 5])
                        ->orderBy('date_end', 'DESC')
                        ->max('cs.date_end');

                        return response()->json(['response' => ['error' => ['Todos los empledos están ocupados en estos momentos, un empleado se encuentra disponible a las '.$last_employee]]], 400);
                    }
                }
            }*/

            $service_client = UserTurn::create([
                'user_id' => $user->id,
                'branch_id' => $branch->id,
                'service_type' => 'grooming_contract',
                'state' => 1,
            ]);

            $solicited_service = ClientService::on($branch->db_name)->create([
                'employee_id' => request('employee_id'),
                'user_id' => $user->id,
                'user_service_id' => $service_client->id,
                'dni' => $dni,
                'service_id' => request('service_id'),
                #'hours' => $count,
                'date_start' => $date_end,
                'date_end' => request('date_end'),
                'state_id' => 2
            ]);
            $payU = null;
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
                    $price = $service_to_pay->price_per_hour;
                    $payU = PayUHelper::paymentCredit($account_config, json_decode($payment_data->data), $user, request('credit_card_number'), request('credit_card_expiration_date'), request('credit_card_security_code'), $price, request('device'), request('cookie'), request('agent'), 'Pago de servico de aseo');
                    if($payU->transactionResponse->state != 'APPROVED'){
                        return response()->json(['response' => ['error' => ['Error al realizar el pago'], 'data' => [$payU]]], 400);
                    }
                    $log = TransactionLog::create([
                        'user_id' => $user->id,
                        'payment_id' => $payment_data->id,
                        'branch_id' => $branch->id,
                        'service_id' => $service_to_pay->id,
                        'action_id' => 'Grooming',
                        'order_id' => $payU->transactionResponse->orderId,
                        'transaction_id' => $payU->transactionResponse->transactionId,
                    ]);
                }
            }

            }catch(Exception $e){
                DB::rollback();
                DB::connection($branch->db_name)->rollback();
                return response()->json(['response' => ['error' => [$e->getMessage()]]],400);
            }

        DB::commit();
        DB::connection($branch->db_name)->commit();
        return response()->json(['response' => 'Servicio solicitado con exito.', 'servicio' => $solicited_service->id, 'payU' => $payU], 200);
    }

    public function cancelService(Request $request, $id)
    {
        $user_turn = UserTurn::select('user_turn.id', 'u.id as user_id', 'bo.id as company_id', 'u.name as user_name', 'bo.name as company_name', 'user_turn.service_type')
        ->join('users as u', 'user_turn.user_id', 'u.id')
        ->join('branch_office as bo', 'user_turn.branch_id', 'bo.id')
        ->where('user_turn.user_id', Auth::id())
        ->where('user_turn.id', $id)
        ->where('user_turn.state', 1)
        ->first();


        if(!$user_turn){
            return response()->json(['response' => ['error' => ['Servicio no encontrado.']]], 400);
        }


        $branch = BranchOffice::find($user_turn->company_id);

        # --------------------- Set connection ------------------------------------#
        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        $client_service = ClientService::on($branch->db_name)->select('id', 'state_id')
        ->where('client_service.user_id', $user_turn->user_id)
        ->where('client_service.user_service_id', $user_turn->id)
        ->whereIn('client_service.state_id', [1, 4])
        ->first();

        if(!$client_service){
            return response()->json(['response' => ['error' => ['El servicio no fue encontrado']]], 400);
        }

        $client_service->state_id = 3;
        $client_service->update();

        return response()->json(['response' => 'Success'], 200);
    }

}
