<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
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
}
