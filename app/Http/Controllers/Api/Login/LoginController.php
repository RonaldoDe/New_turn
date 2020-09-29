<?php

namespace App\Http\Controllers\Api\Login;

use App\Http\Controllers\Api\Templates\TemplatesHelper;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SendEmailHelper;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = Client::find(1);
    }

    public function login(Request $request)
    {

        $validator=\Validator::make($request->all(),[
            'username' => 'required|email',
            'password' => 'required|min:6',

            ]);

            if($validator->fails())
            {
                return response()->json(['response' => ['error' => $errors=$validator->errors()->all()]], 400);
            }
            else
            {
          //validar que el usuario exista
          $user=DB::table('users as u')->where('u.email',request('username'))->where('state_id', 1)
          ->first();
          if($user != null){
                //hashear la contraseña y validar
                if (Hash::check(request('password'), $user->password)) {
                    DB::table('oauth_access_tokens')->where('user_id', $user->id)->delete();

                    //agregar parametros al request
                    $params = [
                        'grant_type' => 'password',
                        'client_id' => $this->client->id,
                        'client_secret' => $this->client->secret,
                        'username' => request('username'),
                        'password' => request('password'),
                        'scope' => '*'
                    ];
                    //agregar parametros al request
                    $request->request->add($params);
                    $proxy = Request::create('oauth/token', 'POST');

                    return Route::dispatch($proxy);
            }else{
                return response()->json(['response' => ['error' => ['Usuario o contraseña incorrectas']]], 400);
            }
        }else{
            return response()->json(['response' => ['error' => ['Usuario no encontrado']]], 401);
          }


        }

    }


    public function refresh(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required'
        ]);

        $params = [
            'grant_type' => 'refresh_token',
            'client_id' => $this->client->id,
            'client_secret' => $this->client->secret,
            'username' => request('username'),
            'password' => request('password')
        ];

        $request->request->add($params);

         $proxy = Request::create('oauth/token', 'POST');

        return Route::dispatch($proxy);
    }


    public function logout(Request $request)
    {
      $access_token = Auth::user()->token();

        DB::table('oauth_refresh_tokens')
        ->where('access_token_id', $access_token->id)
        ->update(['revoked' => true]);

        $access_token->revoke();

      DB::table('oauth_access_tokens')->where('user_id', Auth::id())->delete();

      return response()->json(['message' => 'La sesion a sido cerrada con exito'], 200);


    }

    public function registerUser(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'name' => 'bail|required',
            'last_name' => 'bail|required',
            'email' => 'required|email|unique:users,email',
            'dni' => 'required|',
            'password' => 'required|',
            'phone' => 'bail|',
            'address' => 'bail|',
            'user_type' => 'bail|required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }



        $user = User::create([
            'name' => request('name'),
            'last_name' => request('last_name'),
            'phone' => request('phone'),
            'address' => request('address'),
            'dni' => request('dni'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'phanton_user' => 0,
            'state_id' => 1,
            'password_verify' => 0,
            'email_verify' => 0,
            'user_type' => request('user_type')
        ]);

        if($user->user_type == 'registered'){
            # Here we will generate a code to verify the email
            while(TRUE){
                # Here we create a code
                $email_code = uniqid(rand(1000, 9999), true);
                $password_code = uniqid(rand(1000, 9999), true);
                # Here we check if there is a User that has the same email verification code
                $code_email_exist = User::where('email_code', $email_code)->first();
                $code_password_exist = User::where('password_code', $password_code)->first();
                # If there is not, we exit the loop
                if (!$code_email_exist && !$code_password_exist){
                    break;
                }
            }

            $data = array(
                'password_code' => $password_code,
                'email_code' => $email_code,
                'name' => $user->name." ".$user->last_name,
                'email' => $user->email,
            );
            # We obtain the user's data to send the mail
            $principal_email = array((object)['email' => $user->email, 'name' => $user->name." ".$user->last_name]);
            $to_name = $user->name;
            $to_email = $user->email;
            Mail::send("email_verify", $data, function($message) use ($to_name, $to_email) {
            $message->to($user->email, $user->name." ".$user->last_name)
            ->subject("Correo de verificación de cuenta");
            $message->from('tuturnocolapp@gmail.com',"App Tuturno");
            });
            #Send email
            /*$send_email = SendEmailHelper::sendEmail('Correo de verificación de cuenta.', TemplatesHelper::emailVerify($data), $principal_email, array());
            if($send_email != 1){
                return response()->json(['response' => ['error' => [$send_email]]], 400);
            }*/
        }

        return response()->json(['response' => 'success'], 200);
    }
}
