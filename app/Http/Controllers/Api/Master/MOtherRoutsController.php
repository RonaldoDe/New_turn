<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MOtherRoutsController extends Controller
{
    public function userState(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'branch_id' => 'integer|exists:branch_office,id',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
        if(empty(request('branch_id'))){
            $state = DB::table('user_state')->get();
        }else{
            # --------------------- Set connection ------------------------------------#
            $branch = BranchOffice::where('id', '!=', 1)->find(request('branch_id'));

            if(!$branch){
                return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
            }

            $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
            # --------------------- Set connection ------------------------------------#

            $state = DB::connection($branch->db_name)->table('user_state')->get();


        }

        return response()->json(['response' => $state], 200);
    }

    public function companyState(Request $request)
    {

        $state = DB::table('company_state')->get();

        return response()->json(['response' => $state], 200);
    }

    public function companyType(Request $request)
    {

        $type = DB::table('company_type')->get();

        return response()->json(['response' => $type], 200);
    }

    public function branchState(Request $request)
    {

        $state = DB::table('branch_state')->get();

        return response()->json(['response' => $state], 200);
    }
}
