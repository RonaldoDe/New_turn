<?php

namespace App\Http\Controllers\Api\Administration\Grooming;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RequestServiceController extends Controller
{
    public function requestService(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'required|integer|exists:branch_office,id',
            'service_id' => 'required|integer',
            'pay_on_line' => 'bail|integer|required',
            'payment_data_id' => 'bail|integer',
            'credit_card_number' => 'bail|integer',
            'credit_card_expiration_date' => 'bail',
            'credit_card_security_code' => 'bail|integer',
            'employee_id' => 'bail|integer',
            'dni' => 'bail|',
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

        $company_data = CompanyData::on($branch->db_name)->find(1);

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
}
