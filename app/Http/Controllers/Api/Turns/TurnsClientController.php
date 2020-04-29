<?php

namespace App\Http\Controllers\Api\Turns;

use App\Http\Controllers\Controller;
use App\Models\UserTurn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TurnsClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_turn = UserTurn::select('c.name as company_name', 'c.description as company_description', 'bo.id as branch_id', 'bo.name as branch_name', 'bo.description as branch_description', 'ct.name as company_type', 'user_turn.service_type as type_turn', 'user_turn.created_at')
        ->join('branch_office as bo', 'user_turn.branch_id', 'bo.id')
        ->join('company as c', 'bo.company_id', 'c.id')
        ->join('company_type as ct', 'c.type_id', 'ct.id')
        ->where('user_turn.user_id', Auth::id())
        ->get();

        return response()->json(['response' => $user_turn], 400);
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
            'branch_id' => 'required|integer|exists:branch_office,id',
            'service_id' => 'required|integer',
            'pay_on_line' => 'bail|integer|required',
            'payment_data_id' => 'bail|integer|exist:payment_data,id',
            'credit_card_number' => 'bail|integer',
            'credit_card_expiration_date' => 'bail|integer',
            'credit_card_security_code' => 'bail|integer',
        ]);
        if($validator->fails())
        {
          return response()->json(['response' => ['error' => $validator->errors()->all()]],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
