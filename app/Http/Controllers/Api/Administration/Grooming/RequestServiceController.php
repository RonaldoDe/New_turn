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
            'employee_id' => 'bail|integer',
            'total_minutes' => 'bail|required|integer',
            'date_start' => 'bail|required|date_format:"Y-m-d H:i:s"|date',
            'date_end' => 'bail|required|date_format:"Y-m-d H:i:s"|date',
            'pay_on_line' => 'bail|integer|required',
            'payment_data_id' => 'bail|integer',
            'credit_card_number' => 'bail|integer',
            'credit_card_expiration_date' => 'bail',
            'credit_card_security_code' => 'bail|integer',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $user = User::find(Auth::id());

        # --------------------- Set connection ------------------------------------#
        $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        $company_data = GCompanyData::on($branch->db_name)->find(1);

        $service = Service::on($branch->db_name)->find(request('service_id'));

        if(!$service){
            return response()->json(['response' => ['error' => ['Servicio no encontrado']]], 400);
        }

        # Validate opening hours
        $validate_day = HelpersData::validateDay(request('date_start'), request('date_end'), $service);

        if($validate_day != 1){
            return response()->json(['response' => ['error' => $validate_day]], 400);
        }


        DB::beginTransaction();
        DB::connection($branch->db_name)->beginTransaction();
        try{

            $service_client = UserTurn::create([
                'user_id' => $user->id,
                'branch_id' => $branch->id,
                'service_type' => 'grooming_contract',
            ]);

            if(request('date_end') <= request('date_start')){
                return response()->json(['response' => ['error' => ['La fecha de inicio debe ser menor que la fecha final.']]], 400);
            }

            $current_date_more_waiting = date('Y-m-d H:i:s', strtotime('+'.$service->wait_time.' minute', strtotime(date('Y-m-d H:i:s'))));

            if(request('date_start') < $current_date_more_waiting){
                return response()->json(['response' => ['error' => ['La fecha de inicio debe ser mayor que la fecha actual MAS '.$service->wait_time.' minutos de la llegada de el empleado..']]], 400);
            }

            # unit of measurement
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
            }


            $date_start = new DateTime(request('date_start'));
            $date_end = new DateTime(request('date_end'));
            $diff = $date_start->diff($date_end);

            $hours = $diff->h;
            $minutes = $diff->i;

            $total_diff = ($hours * 60) + $minutes;

            if($total_diff != request('total_minutes')){
                return response()->json(['response' => ['error' => ['El total de minutos y el rango de fechas no coinciden.']]], 400);
            }


            $suggested_employee = null;
            if(!empty(request('employee_id'))){
                $suggested_employee = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')
                ->join('user_has_role as ur', 'users.id', 'ur.user_id')
                ->join('client_service as cs', 'users.id', 'cs.employee_id')
                ->where('ur.role_id', 2)
                ->where('cs.employee_id', request('employee_id'))
                ->whereIn('cs.state_id', [1, 3, 4, 6])
                ->first();

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

            }

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
                    $price = $service_to_pay*$count;
                    $payU = PayUHelper::paymentCredit($account_config, json_decode($payment_data->data), $user, request('credit_card_number'), request('credit_card_expiration_date'), request('credit_card_security_code'), $price);
                    $log = TransactionLog::create([
                        'user_id' => $user->id,
                        'payment_id' => $payment_data->id,
                        'branch_id' => $branch->id,
                        'service_id' => $service_to_pay->id,
                        'action_id' => 'Grooming'
                    ]);
                }
            }


            $solicited_service = ClientService::on($branch->db_name)->create([
                'employee_id' => $suggested_employee->id,
                'user_id' => $user->id,
                'user_service_id' => $service_client->id,
                'dni' => $user->dni,
                'service_id' => request('service_id'),
                'hours' => $count,
                'date_start' => request('date_start'),
                'date_end' => request('date_end'),
                'state_id' => 1
            ]);

            }catch(Exception $e){
                DB::rollback();
                DB::connection($branch->db_name)->rollback();
                return response()->json(['response' => ['error' => [$e->getMessage()]]],400);
            }

        DB::commit();
        DB::connection($branch->db_name)->commit();
        return response()->json(['response' => 'Servicio solicitado con exito.', 'servicio' => $solicited_service->id], 200);
    }
}
