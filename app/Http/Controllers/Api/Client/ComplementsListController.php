<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\CUser;
use App\Models\Master\BranchOffice;
use App\Models\Master\MasterCompany;
use App\Models\PaymentData;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComplementsListController extends Controller
{
    public function servicesList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'bail|required|exists:branch_office,id'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        # --------------------- Set connection ------------------------------------#
        $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        $services = Service::on($branch->db_name)->get();

        return response()->json(['response' => $services], 200);
    }

    public function employeesList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'bail|required|exists:branch_office,id',
            'name' => 'bail'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        # --------------------- Set connection ------------------------------------#
        $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $compnay = MasterCompany::find($branch->company_id);

        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        if($compnay->type_id == 1){
            $employees = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')
            ->join('user_has_role as ur', 'users.id', 'ur.user_id')
            ->where('ur.role_id', 2)
            ->where('users.phanton_user', 0)
            ->name(request('name'))
            ->get();
        }else{
            $employees = CUser::on($branch->db_name)->select('users.id','users.name', 'users.last_name')
            ->join('user_has_role as ur', 'users.id', 'ur.user_id')
            ->where('ur.role_id', 2)
            ->name(request('name'))
            ->get();
        }


        return response()->json(['response' => $employees], 200);
    }

    public function paymentData(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'payment_method' => 'bail|required|exists:payment_data,id',
            'data' => 'bail|required'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $payment = PaymentData::create([
            'user_id' => Auth::id(),
            'payment_method' => request('payment_method'),
            'data' => json_encode(request('data')),
            'state' => 1,
        ]);

        return response()->json(['response' => 'Succes'], 200);
    }

    public function listPaymentData(Request $request)
    {

        $payment = PaymentData::where('user_id', Auth::id())->get();

        return response()->json(['response' => $payment], 200);
    }
}
