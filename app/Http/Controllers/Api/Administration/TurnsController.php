<?php

namespace App\Http\Controllers\Api\Administration;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TurnsController extends Controller
{
    public function __construct()
    {
        # List turn permission
        $this->middleware('permission:/list_turns')->only(['turnsList']);
        # Get connection
        $this->middleware('set_connection');

    }

    public function turnsList(Reuqest $request)
    {

    }
}
