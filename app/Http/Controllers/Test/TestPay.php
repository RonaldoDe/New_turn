<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class TestPay extends Controller
{
    public function tets()
    {

        $body = ['headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
        ],
        ['json' => json_encode(array(
            'language' => 'es',
            'command' => 'command',
            'merchant' => array(
                'apiKey' => '4Vj8eK4rloUd272L48hsrarnUA',
                'apiLogin' => 'pRRXKOl8ikMmt9u',
            ),
            'transaction' => array(
                'order' => array(
                    'accountId' => '512321',
                    'referenceCode' => 'TestPayU',
                    'description' => 'payment test',
                    'language' => 'es',
                    'signature' => '7ee7cf808ce6a39b17481c54f2c57acc',
                    'notifyUrl' => 'http://www.tes.com/confirmation',
                    'additionalValues' => array(
                        'TX_VALUE' => array(
                            'value' => 20000,
                            'currency' => 'COP',
                        ),
                        'TX_TAX' => array(
                            'value' => 3193,
                            'currency' => 'COP',
                        ),
                        'TX_TAX_RETURN_BASE' => array(
                            'value' => 16806,
                            'currency' => 'COP',
                        )
                    ),
                    'buyer' => array(
                        'merchantBuyerId' => '1',
                        'fullName' => 'First name and second buyer  name',
                        'emailAddress' => 'buyer_test@test.com',
                        'contactPhone' => '7563126',
                        'dniNumber' => '5415668464654',
                        'shippingAddress' => array(
                            'street1' => 'calle 100',
                            'street2' => '5555487',
                            'city' => 'Medellin',
                            'state' => 'Antioquia',
                            'country' => 'CO',
                            'postalCode' => '000000',
                            'phone' => '7563126',
                        )
                    ),
                    'shippingAddress' => array(
                        'street1' => 'calle 100',
                        'street2' => '5555487',
                        'city' => 'Medellin',
                        'state' => 'Antioquia',
                        'country' => 'CO',
                        'postalCode' => '0000000',
                        'phone' => '7563126'
                    )
                ),'payer' => array(
                    'merchantPayerId' => '1',
                    'fullName' => 'First name and second payer name',
                    'emailAddress' => 'payer_test@test.com',
                    'contactPhone' => '7563126',
                    'dniNumber' => '5415668464654',
                    'billingAddress' => array(
                        'street1' => 'calle 93',
                        'street2' => '125544',
                        'city' => 'Bogota',
                        'state' => 'Bogota DC',
                        'country' => 'CO',
                        'postalCode' => '000000',
                        'phone' => '7563126'
                    )
                ),
                'creditCard' => array(
                    'number' => '4097440000000004',
                    'securityCode' => '321',
                    'expirationDate' => '2014/12',
                    'name' => 'REJECTED'
                ),
                'extraParameters' => array(
                    'INSTALLMENTS_NUMBER' => 1
                ),
                'type' => 'AUTHORIZATION_AND_CAPTURE',
                'paymentMethod' => 'VISA',
                'paymentCountry' => 'CO',
                'deviceSessionId' => 'vghs6tvkcle931686k1900o6e1',
                'ipAddress' => '127.0.0.1',
                'cookie' => 'pt1t38347bs6jc9ruv2ecpv7o2',
                'userAgent' => 'Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0'
            ),
            'test' => true
        )
        )]];

        # return response()->json($body, 400);


        # Basic url and maximum execution time
        $connection = new Client([
            'base_uri' => 'https://sandbox.api.payulatam.com/payments-api/4.0/',
            'timeout' => 9.0
        ]);

        $get_order = $connection->post("service.cgi", $body);
        $get = $get_order->getBody()->getContents();
        return response()->json($get, 200);

    }
}
