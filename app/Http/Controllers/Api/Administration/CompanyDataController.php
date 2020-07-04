<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use App\Models\Master\BranchOffice;
use App\Models\CompanyData;
use App\Models\Master\MasterCompany;
use Illuminate\Http\Request;

class CompanyDataController extends Controller
{
    public function __construct()
    {
        # the middleware list permission
        $this->middleware('permission:/show_branch')->only(['show', 'index']);
        # the middleware create, edit, delete role
        $this->middleware('permission:/update_branch')->only(['store', 'update', 'destroy']);

        $this->middleware('set_connection');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $company_data = CompanyData::on('connectionDB')->find(1);

        $branch = BranchOffice::select('id', 'name', 'nit', 'email', 'city', 'longitude', 'latitude', 'address', 'phone', 'close', 'company_id')
        ->find($company_data->company_id);

        $company = MasterCompany::find($branch->company_id);

        if($company->type_id == 2){
            $branch->api_k = $company_data->api_k;
            $branch->api_l = $company_data->api_l;
            $branch->mer_id = $company_data->mer_id;
            $branch->acc_id = $company_data->acc_id;
            $branch->pay_on_line = $company_data->pay_on_line;
        }else{
            $branch->api_k = $company_data->api_k;
            $branch->api_l = $company_data->api_l;
            $branch->mer_id = $company_data->mer_id;
            $branch->acc_id = $company_data->acc_id;
            $branch->pay_on_line = $company_data->pay_on_line;
        }

        return response()->json(['response' => $branch], 200);

    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator=\Validator::make($request->all(),[
            'description' => 'required',
            'email' => 'required|email|unique:branch_office,email',
            'city' => 'required',
            'longitude' => 'required',
            'latitude' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'minimun_time' => 'required',
            'close' => 'required',
            'api_k' => 'bail',
            'api_l' => 'bail',
            'mer_id' => 'bail',
            'acc_id' => 'bail',
            'pay_on_line' => 'required',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $company_data = CompanyData::on('connectionDB')->find(1);

        $branch = BranchOffice::select('id', 'name', 'nit', 'email', 'city', 'longitude', 'latitude', 'address', 'phone', 'close', 'company_id', 'minimun_time')
        ->find($company_data->company_id);

        $company = MasterCompany::find($branch->company_id);

        if($company->type_id == 2){
            $branch->description = request('description');
            $branch->email = request('email');
            $branch->city = request('city');
            $branch->longitude = request('longitude');
            $branch->latitude = request('latitude');
            $branch->address = request('address');
            $branch->phone = request('phone');
            $branch->minimun_time = request('minimun_time');
            $branch->close = request('close');
            $company_data->api_k = request('api_k');
            $company_data->api_l = request('api_l');
            $company_data->mer_id = request('mer_id');
            $company_data->acc_id = request('acc_id');
            $company_data->pay_on_line = request('pay_on_line');
            $branch->update();
            $company_data->update();
        }else{
            $branch->description = request('description');
            $branch->email = request('email');
            $branch->city = request('city');
            $branch->longitude = request('longitude');
            $branch->latitude = request('latitude');
            $branch->address = request('address');
            $branch->phone = request('phone');
            $branch->minimun_time = request('minimun_time');
            $branch->close = request('close');
            $company_data->api_k = request('api_k');
            $company_data->api_l = request('api_l');
            $company_data->mer_id = request('mer_id');
            $company_data->acc_id = request('acc_id');
            $company_data->pay_on_line = request('pay_on_line');
            $branch->update();
            $company_data->update();
        }

        return response()->json(['response' => 'Success'], 200);
    }

}
