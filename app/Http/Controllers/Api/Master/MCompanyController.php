<?php

namespace App\Http\Controllers\Api\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\BranchOffice;
use App\Models\Master\MasterCompany;
use Illuminate\Http\Request;

class MCompanyController extends Controller
{
    public function __construct()
    {
        # the middleware list company
        $this->middleware('permission:/list_company')->only(['show', 'index']);
        # the middleware create, edit, delete company
        $this->middleware('permission:/create_company')->only(['store', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = MasterCompany::name(request('name'))
        ->where('id', '!=', 1)->get();

        foreach ($companies as $company) {
            $branches = BranchOffice::where('company_id', $company->id)
            ->get();

            $company->branches = $branches;
        }

        return response()->json(['response' => $companies], 200);
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
            'name' => 'required|min:1|max:125',
            'description' => 'required',
            'nit' => 'required|min:1|max:30',
            'email' => 'required|email',
            'type_id' => 'required|numeric|exists:company_type,id',
            'state_id' => 'required|numeric|exists:company_state,id'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $company = MasterCompany::create([
            'name' => request('name'),
            'description' => request('description'),
            'nit' => request('nit'),
            'email' => request('email'),
            'type_id' => request('type_id'),
            'state_id' => request('state_id')
        ]);

        return response()->json(['response' => 'Empresa creada con exito.'], 200);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $company = MasterCompany::where('id', '!=', 1)->find($id);

        $branches = BranchOffice::where('company_id', $company->id)
        ->get();

        $company->branches = $branches;


        return response()->json(['response' => $company], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator=\Validator::make($request->all(),[
            'name' => 'required|min:1|max:125',
            'description' => 'required',
            'nit' => 'required|min:1|max:30',
            'email' => 'required|email',
            'type_id' => 'required|numeric|exists:company_type,id',
            'state_id' => 'required|numeric|exists:company_state,id'
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }

        $company = MasterCompany::where('id', '!=', 1)->find($id);

        if(!$company){
            return response()->json(['response' => ['error' => ['Empresa no encontrada.']]], 404);
        }

        $company->name = request('name');
        $company->description = request('description');
        $company->nit = request('nit');
        $company->email = request('email');
        $company->type_id = request('type_id');
        $company->state_id = request('state_id');

        $company->update();

        return response()->json(['response' => 'Empresa actualizada con exito.'], 200);
    }

}
