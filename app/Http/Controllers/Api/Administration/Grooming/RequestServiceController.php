<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Helper\PayUHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SetConnectionHelper;
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
            'hours_number' => 'bail|integer',
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

        DB::beginTransaction();
        DB::connection($branch->db_name)->beginTransaction();
        try{

            $service_client = UserTurn::create([
                'user_id' => $user->id,
                'branch_id' => $branch->id,
                'service_type' => 'grooming_contract',
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
                    $payU = PayUHelper::paymentCredit($account_config, json_decode($payment_data->configuration), $user, request('credit_card_number'), request('credit_card_expiration_date'), request('credit_card_security_code'), $service_to_pay->price_per_hour);
                    $log = TransactionLog::create([
                        'user_id' => $user->id,
                        'payment_id' => $payment_data->id,
                        'branch_id' => $branch->id,
                        'service_id' => $service_to_pay->id,
                        'action_id' => 'Barber'
                    ]);
                }
            }

            $service = Service::on($branch->db_name)->find(request('service_id'));
            if(!$service){
                return response()->json(['response' => ['error' => ['Servicio no encontrado']]], 400);
            }

            if(request('date_end') < request('date_start')){
                return response()->json(['response' => ['error' => ['La fecha de inicio debe ser menor que la fecha final.']]], 400);
            }



            $date_start = new DateTime(request('date_start'));
            $date_end = new DateTime(request('date_end'));
            $diff = $date_start->diff($date_end);

            $hours = $diff->h;
            $minutes = $diff->i;

            $total_time = ($hours * 60) + $minutes;

            # unit of measurement
            $i = 0;
            # Number of unit per hours to pay
            $count = 0;
            while(TRUE){
                $count++;
                $i += $service->unit_per_hour;
                if($i >= $total_time){
                    break;
                }
            }

            $test = strtotime('225');

            return response()->json(['response' => ['hours' => $hours, 'minutes' => $minutes, 'test' => $test]], 400);


           /* if($hours_number > $service->hours_max){
                return response()->json(['response' => ['error' => ['Para solicitar este servicio re requiere un maximo de '.$service->hours_max.' horas']]], 400);
            }

            return response()->json(['response' => $hours_number], 400);


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
                ]);*/
                return response()->json(['response' => $service_client], 400);
            }catch(Exception $e){
                DB::rollback();
                DB::connection($branch->db_name)->rollback();
                return response()->json(['response' => ['error' => [$e->getMessage()]]],400);
            }

        DB::commit();
        DB::connection($branch->db_name)->commit();
        # return response()->json(['response' => 'Turno reservado con exito', 'turn' => $turn->id], 200);
    }
}
