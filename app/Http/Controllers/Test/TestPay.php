<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\PayUHelper;
use App\Http\Controllers\Helper\SendEmailHelper;
use App\Http\Controllers\Helper\TemplateHelper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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
            'email' => 'rcamacho12@misena.edu.co',
        );
        $principal_email = array((object)['email' => 'rcamacho12@misena.edu.co', 'name' => 'Ronaldo camacho']);

        /*$to_name = 'Alfredo';
        $to_email = 'rcamacho12@misena.edu.co';
        Mail::send("forget_password", $data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
        ->subject("Test");
        $message->from('tuturnocolapp@gmail.com',"Test turno");
        });*/
        $send_email = SendEmailHelper::sendEmail('Correo de verificación de cuenta.', TemplateHelper::emailVerify($data), $principal_email, array());
        if($send_email != 1){
            return response()->json(['response' => ['error' => [$send_email]]], 400);
        }

        return response()->json(['response' => 'Success'], 200);
    }
}
