<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\PayUHelper;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestPay extends Controller
{
    public function tets()
    {
        $payment = PayUHelper::paymentApi();

        return response()->json(['response' => $payment], 400);
    }
}
