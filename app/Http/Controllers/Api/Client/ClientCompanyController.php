<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\ClientTurn;
use App\Models\CompanyData;
use App\Models\Master\BranchOffice;
use App\Models\Master\MasterCompany;
use Illuminate\Http\Request;

class ClientCompanyController extends Controller
{
    public function companyList(Request $request)
    {
        $companies = MasterCompany::where('id', '!=', 1)
        ->where('type_id', '!=', 1)
        ->where('state_id', 1)
        ->get();

        return response()->json(['response' => $companies], 200);
    }

    public function branchesList(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'company_id' => 'bail|required|exists:company,id',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $company = MasterCompany::where('id', '!=', 1)->find(request('company_id'));

        if(!$company){
            return response()->json(['response' => ['error' => ['Empresa no encontrada']]], 400);
        }

        $branches = BranchOffice::select('branch_office.id', 'branch_office.name', 'branch_office.description', 'branch_office.nit', 'branch_office.email', 'branch_office.city', 'branch_office.longitude',
        'branch_office.latitude', 'branch_office.address', 'branch_office.phone', 'branch_office.close', 'branch_office.hours_24', 'branch_office.state_id', 'branch_office.company_id', 'branch_office.created_at',
        'branch_office.updated_at', 'c.name as company_name', 'c.type_id as company_type', 'ct.name as company_type_name', 'ct.description as company_type_descrption')
        ->join('company as c', 'branch_office.company_id', 'c.id')
        ->join('company_type as ct', 'c.type_id', 'ct.id')
        ->where('branch_office.close', 0)
        ->where('branch_office.state_id', 1)
        ->where('branch_office.company_id', request('company_id'))
        ->get();

        return response()->json(['response' => $branches], 200);
    }

    public function branchDetail(Request $request, $id)
    {
        $branch_validator = BranchOffice::where('id', '!=', 1)
        ->where('state_id', 1)
        ->find($id);

        if(!$branch_validator){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada']]], 400);
        }

        $branch = BranchOffice::where('close', 0)
        ->where('state_id', 1)
        ->find($id);

        if(!$branch){
            return response()->json(['response' => ['error' => ['Sucursal no encontrada.']]], 400);
        }

        #Set conection
        $set_new_connection = SetConnectionHelper::setByDBName($branch->db_name);

        #Branch data
        $company_data = CompanyData::on($branch->db_name)->find(1);

        if(!$branch->hours_24){
            $turns_on_hold = ClientTurn::on($branch->db_name)->where('state_id', 2)->where('today', date('Y-m-d'))->count('turn_number');
            $turns_in_process = ClientTurn::on($branch->db_name)->select('turn_number')->where('state_id', 1)->where('today', date('Y-m-d'))->get();
            $current_turn = ClientTurn::on($branch->db_name)->select('turn_number')->where('state_id', 1)->where('today', date('Y-m-d'))->max('turn_number');
            $last_turn = ClientTurn::on($branch->db_name)->select('turn_number')->whereIn('state_id', [1, 2])->where('today', date('Y-m-d'))->get();

            $branch->turns_on_hold = $turns_on_hold;
            $branch->turns_in_process = $turns_in_process;
            $branch->current_turn = $current_turn;
            $branch->turns_total = count($last_turn);
        }else{
            $turns_on_hold = ClientTurn::on($branch->db_name)->where('state_id', 2)->where('c_return', $company_data->current_return)->count('turn_number');
            $turns_in_process = ClientTurn::on($branch->db_name)->where('state_id', 1)->where('c_return', $company_data->current_return)->count('turn_number');
            $current_turn = ClientTurn::on($branch->db_name)->select('turn_number')->where('state_id', 1)->where('c_return', $company_data->current_return)->max('turn_number');
            $last_turn = ClientTurn::on($branch->db_name)->select('turn_number')->whereIn('state_id', [1, 2])->where('c_return', $company_data->current_return)->get();

            $branch->turns_on_hold = $turns_on_hold;
            $branch->turns_in_process = $turns_in_process;
            $branch->current_turn = $current_turn;
            $branch->turns_total = count($last_turn);
        }

        return response()->json(['response' => $branch], 200);
    }
}
