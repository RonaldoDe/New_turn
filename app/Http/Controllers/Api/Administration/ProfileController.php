<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SendEmailHelper;
use App\User;
use Illuminate\Http\Request;

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
            $password_code = uniqid(rand(1000, 9999), true);
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

        $send_email = SendEmailHelper::sendEmail('Olvido de contraseña.', TemplatesHelper::forgetPassword($data), $principal_email, array());
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

        $user_password = User::where('code_password_verify', $password)
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
}
