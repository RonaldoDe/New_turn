<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

//prueba del path
require_once public_path('lib/PayU.php');

class PayUHelper extends Controller
{
    public static function paymentCredit($account_config, $payer_data, $buyer_data, $card_number, $expiration_date, $card_code, $price, $device, $cookie, $agent)
    {

        \PayU::$apiKey = $account_config['api_k']; //  Ingrese aquí su propio apiKey.
        \PayU::$apiLogin = $account_config['api_l']; //Ingrese aquí su propio apiLogin.
        \PayU::$merchantId = $account_config['mer_id']; //Ingrese aquí su Id de Comercio.
        \PayU::$language = \SupportedLanguages::ES; //Seleccione el idioma.
        \PayU::$isTest = true; //Dejarlo True cuando sean pruebas.

        $reference = "1";
        $value = $price;

        \Environment::setPaymentsCustomUrl("https://api.payulatam.com/payments-api/4.0/service.cgi");
        \Environment::setReportsCustomUrl("https://api.payulatam.com/reports-api/4.0/service.cgi");
        \Environment::setSubscriptionsCustomUrl("https://api.payulatam.com/payments-api/rest/v4.9/");

        //para realizar un pago con tarjeta de crédito---------------------------------
        $parameters = array(
            //Ingrese aquí el identificador de la cuenta.
            \PayUParameters::ACCOUNT_ID => $account_config['acc_id'],
            //Ingrese aquí el código de referencia.
            \PayUParameters::REFERENCE_CODE => $reference,
            //Ingrese aquí la descripción.
            \PayUParameters::DESCRIPTION => "Primer pago",

            // -- Valores --
            //Ingrese aquí el valor.
            \PayUParameters::VALUE => $value,

            //Ingrese aquí el valor del IVA (Impuesto al Valor Agregado solo valido para Colombia) de la transacción,
            //si se envía el IVA nulo el sistema aplicará el 19% automáticamente. Puede contener dos dígitos decimales.
            //Ej: 19000.00. En caso de no tener IVA debe enviarse en 0.
            \PayUParameters::TAX_VALUE => "0",
            //Ingrese aquí el valor base sobre el cual se calcula el IVA (solo valido para Colombia).
            //En caso de que no tenga IVA debe enviarse en 0.
            \PayUParameters::TAX_RETURN_BASE => "0",

            //Ingrese aquí la moneda.
            \PayUParameters::CURRENCY => "COP",

            // -- Comprador
            //Ingrese aquí el nombre del comprador.
            \PayUParameters::BUYER_NAME => $buyer_data->name. ' ' .$buyer_data->last_name,
            //Ingrese aquí el email del comprador.
            \PayUParameters::BUYER_EMAIL => $buyer_data->email,
            //Ingrese aquí el teléfono de contacto del comprador.
            \PayUParameters::BUYER_CONTACT_PHONE => $buyer_data->phone,
            //Ingrese aquí el documento de contacto del comprador.
            \PayUParameters::BUYER_DNI => $buyer_data->cc_dni,
            //Ingrese aquí la dirección del comprador.
            \PayUParameters::BUYER_STREET => $buyer_data->address_1,
            \PayUParameters::BUYER_STREET_2 => $buyer_data->address_2,
            \PayUParameters::BUYER_CITY => $buyer_data->city,
            \PayUParameters::BUYER_STATE => $buyer_data->state,
            \PayUParameters::BUYER_COUNTRY => "CO",
            \PayUParameters::BUYER_POSTAL_CODE => $buyer_data->postal_code,
            \PayUParameters::BUYER_PHONE => $buyer_data->phone,

            \PayUParameters::PROCESS_WITHOUT_CVV2 => true,

            // -- pagador --
            //Ingrese aquí el nombre del pagador.
            \PayUParameters::PAYER_NAME => $payer_data->full_name,
            //Ingrese aquí el email del pagador.
            \PayUParameters::PAYER_EMAIL => $payer_data->email,
            //Ingrese aquí el teléfono de contacto del pagador.
            \PayUParameters::PAYER_CONTACT_PHONE => $payer_data->phone,
            //Ingrese aquí el documento de contacto del pagador.
            \PayUParameters::PAYER_DNI => $payer_data->dni,
            //Ingrese aquí la dirección del pagador.
            \PayUParameters::PAYER_STREET => $payer_data->address_1,
            \PayUParameters::PAYER_STREET_2 => $payer_data->address_2,
            \PayUParameters::PAYER_CITY => $payer_data->city,
            \PayUParameters::PAYER_STATE => $payer_data->state,
            \PayUParameters::PAYER_COUNTRY => "CO",
            \PayUParameters::PAYER_POSTAL_CODE => $payer_data->postal_code,
            \PayUParameters::PAYER_PHONE => $payer_data->phone,

            // -- Datos de la tarjeta de crédito --
            //Ingrese aquí el número de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_NUMBER => $card_number,
            //Ingrese aquí la fecha de vencimiento de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_EXPIRATION_DATE => $expiration_date,
            //Ingrese aquí el código de seguridad de la tarjeta de crédito
            \PayUParameters::CREDIT_CARD_SECURITY_CODE=> $card_code,
            //Ingrese aquí el nombre de la tarjeta de crédito
            //VISA||MASTERCARD||AMEX||DINERS
            \PayUParameters::PAYMENT_METHOD => $payer_data->payment_method,

            //Ingrese aquí el número de cuotas.
            \PayUParameters::INSTALLMENTS_NUMBER => "1",
            //Ingrese aquí el nombre del pais.
            \PayUParameters::COUNTRY => \PayUCountries::CO,

            //Session id del device.
            \PayUParameters::DEVICE_SESSION_ID => $device,
            //IP del pagadador
            \PayUParameters::IP_ADDRESS => $_SERVER['REMOTE_ADDR'],
            //Cookie de la sesión actual.
            \PayUParameters::PAYER_COOKIE=>date('Y-m-d\TH:i:s'),
            //Cookie de la sesión actual.
            \PayUParameters::USER_AGENT=>$agent
        );

        //solicitud de autorización y captura
        return $response = \PayUPayments::doAuthorizationAndCapture($parameters);
        # return $response;
        //  -- podrás obtener las propiedades de la respuesta --
/*        if($response){
            $response->transactionResponse->orderId;
            $response->transactionResponse->transactionId;
            $response->transactionResponse->state;
            if($response->transactionResponse->state=="PENDING"){
                $response->transactionResponse->pendingReason;
            }
            $response->transactionResponse->paymentNetworkResponseCode;
            $response->transactionResponse->paymentNetworkResponseErrorMessage;
            $response->transactionResponse->trazabilityCode;
            $response->transactionResponse->responseCode;
            $response->transactionResponse->responseMessage;
        }*/
    }

    public static function paymentApi()
    {
        # Fixed headers
        $headers = [
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
            'Content-Length' => 'length'
        ];

        $body = [
            "test"=> false,
            "language"=> "es",
            "command"=> "TRANSACTION_RESPONSE_DETAIL",
            "merchant"=> [
               "apiLogin"=> "pRRXKOl8ikMmt9u",
               "apiKey"=> "4Vj8eK4rloUd272L48hsrarnUA"
            ],
            "details"=> [
                "transactionId" => "588e3092-6f11-4b77-badf-55695eaa8249"
            ]
        ];

        /*$body = [
                "language" => "es",
                "command" => "SUBMIT_TRANSACTION",
                "merchant" => [
                   "apiLogin" => "pRRXKOl8ikMmt9u",
                   "apiKey" => "4Vj8eK4rloUd272L48hsrarnUA"
                ],
                "transaction" => [
                   "order" => [
                      "accountId" => "512326",
                      "referenceCode" => "testPanama1",
                      "description" => "Test order Panama",
                      "language" => "en",
                      "notifyUrl" => "http =>//pruebaslap.xtrweb.com/lap/pruebconf.php",
                      "signature" => "a2de78b35599986d28e9cd8d9048c45d",
                      "shippingAddress" => [
                         "country" => "PA"
                      ],
                      "buyer" => [
                         "fullName" => "APPROVED",
                         "emailAddress" => "test@payulatam.com",
                         "dniNumber" => "1155255887",
                         "shippingAddress" => [
                            "street1" => "Calle 93 B 17 – 25",
                            "city" => "Panama",
                            "state" => "Panama",
                            "country" => "PA",
                            "postalCode" => "000000",
                            "phone" => "5582254"
                         ]
                      ],
                      "additionalValues" => [
                         "TX_VALUE" => [
                            "value" => 5,
                            "currency" => "USD"
                         ]
                      ]
                   ],
                   "creditCard" => [
                      "number" => "4111111111111111",
                      "securityCode" => "123",
                      "expirationDate" => "2018/08",
                      "name" => "test"
                   ],
                   "type" => "AUTHORIZATION_AND_CAPTURE",
                   "paymentMethod" => "VISA",
                   "paymentCountry" => "PA",
                   "payer" => [
                      "fullName" => "APPROVED",
                      "emailAddress" => "test@payulatam.com"
                   ],
                   "ipAddress" => "127.0.0.1",
                   "cookie" => "cookie_52278879710130",
                   "userAgent" => "Firefox",
                   "extraParameters" => [
                      "INSTALLMENTS_NUMBER" => 1,
                      "RESPONSE_URL" => "http://www.misitioweb.com/respuesta.php"
                   ]
                ],
                "test" => true
        ];*/

        # Basic url and maximum execution time
        $connection = new Client([
            'base_uri' => 'https://sandbox.api.payulatam.com/payments-api/4.0/',
            'timeout' => 9.0
        ]);

        $get_order = $connection->post("service.cgi", ['headers' => $headers, 'body' => json_encode($body)]);
        $get = json_decode($get_order->getBody()->getContents());
        dd($get);
        return $get;
    }
}
