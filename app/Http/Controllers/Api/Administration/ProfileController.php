<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Helper\TemplateHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\HelpersData;
use App\Http\Controllers\Helper\PayUHelper;
use App\Http\Controllers\Helper\SendEmailHelper;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CompanyData;
use App\Models\Grooming\GCompanyData;
use App\Models\Grooming\Service as AppService;
use App\Models\Master\BranchOffice;
use App\Models\Master\MasterCompany;
use App\Models\Master\TransactionLog;
use App\Models\Service;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function newPassword(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'password' => 'required|min:6',
            'new_password' => 'required|confirmed|min:6',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $user = User::find(Auth::id());

        if(!$user){
            return response()->json(['response' => ['error' => ['Usuario no encontrado']]], 400);
        }

        if (Hash::check(request('password'), $user->password)) {

            if(request('password') == request('new_password')){
                return response()->json(['response' => ['error' => ['Por favor insertar una contraseña diferente a la anterior.']]], 400);
            }

            $user->password = bcrypt(request('new_password'));
            $user->password_verify = 1;
            $user->update();
        }else{
            return response()->json(['response' => ['error' => ['Contraseña incorrecta']]], 400);
        }

        return response()->json(['response' => 'Contraseña actualizada'], 200);


    }

    # Send email when the password is forget
    public function forgetPassword(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'email' => 'required|email',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $user = User::where('email', request('email'))->first();
        if(!$user){
            return response()->json(['response' => ['error' => ['Usuario no encontrado']]], 404);
        }

        # Here we will generate a code to verify the email
        while(TRUE){
            # Here we create a code
            $password_code = (rand(1000, 9999));
            # Here we check if there is a User that has the same email verification code
            $code_password_exist = User::where('password_code', $password_code)->first();
            # If there is not, we exit the loop
            if (!$code_password_exist){
                break;
            }
        }
        $data = array(
            'password_code' => $password_code,
            'name' => $user->name." ".$user->last_name,
            'email' => $user->email,
        );

        $user->password_code = $password_code;
        $user->password_verify = 0;
        $user->update();


        $principal_email = array((object)['email' => $user->email, 'name' => $user->name." ".$user->last_name]);

        $send_email = SendEmailHelper::sendEmail('Olvido de contraseña.', TemplateHelper::forgetPassword($data), $principal_email, array());
        if($send_email != 1){
            return response()->json(['response' => ['error' => [$send_email]]], 400);
        }

        return response()->json(['response' => 'Se a enviado un correo con la solicitud de cambio de contraseña.'], 200);

    }

    # First time password update
    public function updateFirstPassword(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'password_code' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        $password = request('password_code');

        $user_password = User::where('password_code', $password)
        ->first();

        if(!$user_password){
            return response()->json(['response' => ['error' => ['Error']]], 404);
        }

        if($user_password->password_verify){
            return response()->json(['response' => ['error' => ['La contraseña ya ha sido cambiada.']]], 400);
        }

        $user_password->password = bcrypt(request('password'));
        $user_password->password_verify = 1;
        $user_password->update();

        return response()->json(['response' => 'Contraseña actualizada'], 200);


    }

    # Verify email first time
    public function validateAcount()
    {
        $password = request('password_code');
        $email = request('email_code');
        $verify_email = 0;

        $user_email = User::where('email_code', $email)
        ->first();

        $user_password = User::where('password_code', $password)
        ->first();

        if(!$user_email && !$user_password){
            return response()->json(['response' => ['error' => ['Error']]], 404);
        }

        if($user_email->email_verify == 0){
            $user_email->email_verify = 1;
            $user_email->email_verified_at = date('Y-m-d H:i:s');
            $user_email->update();

            $verify_email = 1;
        }

        if($user_password->password_verify == 1){
            return response()->json(['response' => ['error' => ['La cuenta ya se encuentra activada y la contraseña ya se ha actualizado.']]], 400);
        }

        if($verify_email){
            return response()->json(['response' => 'Cuenta Verificada', 'data' => $verify_email], 200);
        }else{
            return response()->json(['response' => 'La cuenta ya se encuentra verificada.', 'data' => $verify_email], 200);
        }

    }

    public function transactionList(Request $request)
    {
        $user = User::find(Auth::id());

        $transactions = TransactionLog::where('user_id', $user->id)->get();

        foreach ($transactions as $transaction) {
            $branch = BranchOffice::where('id', $transaction->branch_id)->first();
            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada', 'id '.$transaction->branch_id]]], 400);
            }

            $company = MasterCompany::find($branch->company_id);

            # Set connection to branch
            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);

            $transaction->branch = array(
                'branch_id' => $branch->id,
                'branch_name' => $branch->name,
                'branch_city' => $branch->city,
                'branch_address' => $branch->address,
                'branch_phone' => $branch->phone
            );
            # Barber db
            if($company->type_id == 2){
                $service = Service::on($branch->db_name)->find($transaction->service_id);
                $transaction->service = array(
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price,
                    'time' => $service->time,
                );
            }else{
                $service = AppService::on($branch->db_name)->find($transaction->service_id);
                $transaction->service = array(
                    'name' => $service->name,
                    'description' => $service->description,
                    'price' => $service->price_per_hour,
                    'time' => $service->unit_per_hour,
                );
            }

        }

        return response()->json(['response' => $transactions], 200);
    }

    public function transactionDetail(Request $request, $id)
    {
        $user = User::find(Auth::id());

        $transaction = TransactionLog::where('user_id', $user->id)->where('id', $id)->first();

        if(!$transaction){
            return response()->json(['response' => ['error' => ['Registro no encontrado']]], 400);
        }

        $branch = BranchOffice::where('id', $transaction->branch_id)->first();
        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada', 'id '.$transaction->branch_id]]], 400);
        }

        $company = MasterCompany::find($branch->company_id);

        # Set connection to branch
        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);

        $transaction->branch = array(
            'branch_id' => $branch->id,
            'branch_name' => $branch->name,
            'branch_city' => $branch->city,
            'branch_address' => $branch->address,
            'branch_phone' => $branch->phone
        );
        # Barber db
        if($company->type_id == 2){
            $service = Service::on($branch->db_name)->find($transaction->service_id);
            $transaction->service = array(
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price,
                'time' => $service->time,
            );
        }else{
            $service = AppService::on($branch->db_name)->find($transaction->service_id);
            $transaction->service = array(
                'name' => $service->name,
                'description' => $service->description,
                'price' => $service->price_per_hour,
                'time' => $service->unit_per_hour,
            );
        }


        return response()->json(['response' => $transaction], 200);
    }

    public function repayment(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'reason' => 'required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        $user = User::find(Auth::id());

        $transaction = TransactionLog::where('user_id', $user->id)->where('id', $id)->where('transaction_state', 'APROVED')->first();

        if(!$transaction){
            return response()->json(['response' => ['error' => ['Registro no encontrado o ya pasaron mas de 24 horas']]], 400);
        }

        $branch = BranchOffice::find($transaction->branch_id);

        # Set connection to branch
        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);

        $company = MasterCompany::find($branch->company_id);
        if($company->type_id == 2){
            $company_data = CompanyData::on($branch->db_name)->find(1);

            $account_config = array(
                'api_k' => $company_data->api_k,
                'api_l' => $company_data->api_l,
                'mer_id' => $company_data->mer_id,
                'acc_id' => $company_data->acc_id
            );

            $repayment = PayUHelper::repayment($account_config, $transaction->order_id, $transaction->transaction_id, request('reason'));
        }else{
            $company_data = GCompanyData::on($branch->db_name)->find(1);

            $account_config = array(
                'api_k' => $company_data->api_k,
                'api_l' => $company_data->api_l,
                'mer_id' => $company_data->mer_id,
                'acc_id' => $company_data->acc_id
            );

            $repayment = PayUHelper::repayment($account_config, $transaction->order_id, $transaction->transaction_id, request('reason'));
        }


        return response()->json(['response' => $repayment], 200);
    }
}
