<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\PayUHelper;
use App\Http\Controllers\Helper\SendEmailHelper;
use App\Http\Controllers\Helper\TemplateHelper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestPay extends Controller
{
    public function tets()
    {
        $payment = PayUHelper::paymentApi();

        return response()->json(['response' => $payment], 400);
    }

    public function testEmail(Request $request)
    {
        $data = array(
            'password_code' => '123434',
            'email_code' => '123434',
            'name' => 'Ronaldo camacho',
            'email' => 'ronaldocamachomeza@hotmail.com',
        );
        $principal_email = array((object)['email' => 'ronaldocamachomeza@hotmail.com', 'name' => 'Ronaldo camacho']);

        $send_email = SendEmailHelper::sendEmail('Correo de verificación de cuenta.', TemplateHelper::emailVerify($data), $principal_email, array());
        if($send_email != 1){
            return response()->json(['response' => ['error' => [$send_email]]], 400);
        }

        return response()->json(['response' => 'Success'], 200);
    }
}
