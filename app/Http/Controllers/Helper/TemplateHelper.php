<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class TemplateHelper extends Controller
{
    # Data to send to the password forget template
    public static function forgetPassword($data)
    {
        return view('forget_password')->with([
            'password_code' => $data['password_code'],
            'name' => $data['name'],
            'email' => $data['email']
            ]);
    }

    # Data to send to the account verification email template
    public static function emailVerify($data)
    {
        return view('email_verify')->with([
            'password_code' => $data['password_code'],
            'email_code' => $data['email_code'],
            'name' => $data['name'],
            'email' => $data['email']
        ]);
    }

    # Verify email first time
    public function validateAcount()
    {
        $email = request('email_code');
        $verify_email = 0;

        $user_email = User::where('email_code', $email)
        ->first();

        if(!$user_email){
            return response()->json(['response' => ['error' => ['Error']]], 404);
        }

        if($user_email->email_verify == 0){
            $user_email->email_verify = 1;
            $user_email->email_verified_at = date('Y-m-d H:i:s');
            $user_email->update();

            $verify_email = 1;
        }

        if($verify_email){
            return response()->json(['response' => 'Cuenta Verificada', 'data' => $verify_email], 200);
        }else{
            return response()->json(['response' => 'La cuenta ya se encuentra verificada.', 'data' => $verify_email], 200);
        }

    }
}
