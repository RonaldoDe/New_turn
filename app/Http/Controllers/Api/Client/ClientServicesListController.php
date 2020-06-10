<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Helper\SetConnectionHelper;
use App\Models\ClientTurn;
use App\Models\CUser;
use App\Models\Grooming\ClientService;
use App\Models\Master\BranchOffice;
use App\Models\Service;
use App\Models\UserTurn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientServicesListController extends Controller
{
    public function servicesList(Request $request)
    {

        $user_turn = UserTurn::select('user_turn.id', 'user_turn.user_id', 'c.name as company_name', 'c.description as company_description', 'c.id as company_id', 'bo.id as branch_id', 'bo.name as branch_name', 'bo.description as branch_description', 'ct.name as company_type', 'user_turn.service_type as type_turn', 'user_turn.created_at')
        ->join('branch_office as bo', 'user_turn.branch_id', 'bo.id')
        ->join('company as c', 'bo.company_id', 'c.id')
        ->join('company_type as ct', 'c.type_id', 'ct.id')
        ->where('user_turn.user_id', Auth::id())
        ->where('user_turn.state', 1)
        ->get();


        foreach ($user_turn as $service) {
            if($service->type_turn == 'grooming_contract'){

                $branch = BranchOffice::find($service->branch_id);

                # --------------------- Set connection ------------------------------------#
                $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
                # --------------------- Set connection ------------------------------------#

                $employee_id = ClientService::on($branch->db_name)->select('employee_id')
                ->where('client_service.user_id', $service->user_id)
                ->where('client_service.user_service_id', $service->id)
                ->whereIn('client_service.state_id', [1, 2, 3, 4, 5, 6])
                ->first();

                if($employee_id->employee_id == null){
                    $client_service = ClientService::on($branch->db_name)->select('client_service.state_id')
                    ->where('client_service.user_id', $service->user_id)
                    ->where('client_service.user_service_id', $service->id)
                    ->whereIn('client_service.state_id', [1, 2, 3, 4, 5, 6])
                    ->first();
                }else{
                    $client_service = ClientService::on($branch->db_name)->select('client_service.state_id')
                    ->join('users as u', 'client_service.employee_id', 'u.id')
                    ->where('client_service.user_id', $service->user_id)
                    ->where('client_service.user_service_id', $service->id)
                    ->whereIn('client_service.state_id', [1, 2, 3, 4, 5, 6])
                    ->first();
                }

                $service->turn_state = $client_service->state_id;



            }else{

                $branch = BranchOffice::find($service->branch_id);

                # --------------------- Set connection ------------------------------------#
                $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
                # --------------------- Set connection ------------------------------------#

                $client_turn = ClientTurn::on($branch->db_name)->select('state_id')->where('user_id', $service->user_id)->where('user_turn_id', $service->id)->whereIn('state_id', [2, 4, 1])->first();

                $service->turn_state = $client_turn->state_id;

            }
        }


        return response()->json(['response' => $user_turn], 200);
    }

    public function servicesListDetail(Request $request, $id)
    {

        $user_turn = UserTurn::select('user_turn.id', 'u.id as user_id', 'bo.id as company_id', 'u.name as user_name', 'bo.name as company_name', 'user_turn.service_type')
        ->join('users as u', 'user_turn.user_id', 'u.id')
        ->join('branch_office as bo', 'user_turn.branch_id', 'bo.id')
        ->where('user_turn.user_id', Auth::id())
        ->where('user_turn.id', $id)
        ->where('user_turn.state', 1)
        ->first();


        if(!$user_turn){
            return response()->json(['response' => ['error' => ['Turno no encontrado.']]], 400);
        }


        $branch = BranchOffice::find($user_turn->company_id);

        # --------------------- Set connection ------------------------------------#
        $set_connection = SetConnectionHelper::setByDBName($branch->db_name);
        # --------------------- Set connection ------------------------------------#

        if($user_turn->service_type == 'grooming_contract'){

            $employee_id = ClientService::on($branch->db_name)->select('employee_id')
            ->where('client_service.user_id', $user_turn->user_id)
            ->where('client_service.user_service_id', $user_turn->id)
            ->whereIn('client_service.state_id', [1, 2, 4, 5])
            ->first();

            if($employee_id->employee_id == null){
                $client_service = ClientService::on($branch->db_name)->select('client_service.id', 'client_service.employee_id',
                'client_service.user_id', 'client_service.user_service_id', 'client_service.dni','client_service.start_at', 'client_service.acepted_by', 'client_service.service_id', 'sl.name as service_name',
                'sl.description as service_description', 'sl.price_per_hour', 'sl.unit_per_hour', 'sl.hours_max',
                'client_service.paid_out', 'client_service.hours', 'client_service.date_start', 'client_service.date_end', 'client_service.state_id', 'client_service.created_at', 'client_service.updated_at')
                ->join('service_list as sl', 'client_service.service_id', 'sl.id')
                ->where('client_service.user_id', $user_turn->user_id)
                ->where('client_service.user_service_id', $user_turn->id)
                ->whereIn('client_service.state_id', [1, 2, 3, 4, 5, 6])
                ->first();
            }else{
                $client_service = ClientService::on($branch->db_name)->select('client_service.id', 'client_service.employee_id', 'u.name as employee_name', 'u.last_name as employee_last_name',
                'client_service.user_id', 'client_service.user_service_id', 'client_service.dni','client_service.start_at', 'client_service.acepted_by', 'client_service.service_id', 'sl.name as service_name',
                'sl.description as service_description', 'sl.price_per_hour', 'sl.unit_per_hour', 'sl.hours_max',
                'client_service.paid_out', 'client_service.hours', 'client_service.date_start', 'client_service.date_end', 'client_service.state_id', 'client_service.created_at', 'client_service.updated_at')
                ->join('service_list as sl', 'client_service.service_id', 'sl.id')
                ->join('users as u', 'client_service.employee_id', 'u.id')
                ->where('client_service.user_id', $user_turn->user_id)
                ->where('client_service.user_service_id', $user_turn->id)
                ->whereIn('client_service.state_id', [1, 2, 3, 4, 5, 6])
                ->first();
            }


            return response()->json(['response' => $client_service], 200);

        }else{
            $client_turn = ClientTurn::on($branch->db_name)->where('user_id', $user_turn->user_id)->where('user_turn_id', $user_turn->id)->whereIn('state_id', [2, 4, 1])->first();
            $current_turn = ClientTurn::on($branch->db_name)->where('state_id', 1)->max('turn_number');
            $service = Service::on($branch->db_name)->find($client_turn->service_id);
            $employee = CUser::on($branch->db_name)->select('users.id', 'users.name', 'users.last_name')->find($client_turn->employee_id);
            $client_turn->current_turn = $current_turn;
            $client_turn->service_name = $service->name;
            $client_turn->service_description = $service->description;
            $client_turn->service_time = $service->time;
            $client_turn->employee = $employee;

            return response()->json(['response' => $client_turn], 200);
        }

    }
}
