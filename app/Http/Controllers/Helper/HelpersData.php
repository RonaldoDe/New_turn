<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\CUser;
use Illuminate\Http\Request;

class HelpersData extends Controller
{
    public static function validateDay($date_start, $date_end, $service)
    {
        # Day in string Examnple (Monday)
        $start = strtolower(date('l', strtotime($date_start)));
        $end = strtolower(date('l', strtotime($date_end)));

        # Date in hours Example (08:00:00)
        $start_hout = date('H:i:s', strtotime($date_start));
        $end_hout = date('H:i:s', strtotime($date_end));

        $opening = json_decode($service->opening_hours);

        $pass_start = 0;
        $pass_end = 0;
        if($start == 'sunday'){
            $start = 'holidays';
        }
        if($end == 'sunday'){
            $end = 'holidays';
        }
        for ($i=0; $i < 7; $i++) {
            foreach ($opening[$i] as $key => $value) {
                if($start == $key){
                    foreach ($value as $date) {


                        if($start_hout >= $date->date_start && $start_hout <= $date->date_end){
                           $pass_start += 1;
                        }

                        if($end_hout >= $date->date_start && $end_hout <= $date->date_end){
                            $pass_end += 1;
                         }
                    }


                }
            }
        }

        if($pass_start == 0){
            return 'La fecha de inicio no se encuentra disponible para las horas habiles.';
        }else if($pass_end == 0){
            return 'La fecha final no se encuentra disponible para las horas habiles.';
        }

        return 1;

    }

    public static function validateDayBarber($service)
    {
        # Day in string Examnple (Monday)
        $start = strtolower(date('l', strtotime(date('Y-m-d H:i:s'))));
        # Date in hours Example (08:00:00)
        $start_hout = date('H:i:s', strtotime(date('Y-m-d H:i:s')));

        $opening = json_decode($service->opening_hours);

        $pass_start = 0;
        if($start == 'sunday'){
            $start = 'holidays';
        }
        for ($i=0; $i < 7; $i++) {
            foreach ($opening[$i] as $key => $value) {

                if($start == $key){
                    foreach ($value as $date) {

                        if($start_hout >= $date->date_start && $start_hout <= $date->date_end){
                           $pass_start += 1;
                        }
                    }

                }
            }
        }

        if($pass_start == 0){
            return 'La fecha de inicio no se encuentra disponible para las horas habiles.';
        }
        return 1;

    }

    public static function employeeBusinessDays($date_start, $date_end, $service, $db)
    {
        $employees = CUser::on($db)->select('users.id','users.name', 'users.last_name', 'users.business_days')
        ->join('user_has_role as ur', 'users.id', 'ur.user_id')
        ->join('employee_type_employee as ete', 'users.id', 'ete.employee_id')
        ->join('employee_type_service as ets', 'ete.employee_type_id', 'ets.employee_type_id')
        ->where('ets.service_id', $service)
        ->where('ur.role_id', 2)
        ->get();



        # Day in string Examnple (Monday)
        $start = strtolower(date('l', strtotime($date_start)));
        $end = strtolower(date('l', strtotime($date_end)));

        # Date in hours Example (08:00:00)
        $start_hout = date('H:i:s', strtotime($date_start));
        $end_hout = date('H:i:s', strtotime($date_end));

        $employees_array = array();

        foreach ($employees as $employee) {
            $opening = json_decode($employee->business_days);
            $pass_start = 0;
            $pass_end = 0;
            if($start == 'sunday'){
                $start = 'holidays';
            }
            if($end == 'sunday'){
                $end = 'holidays';
            }
            for ($i=0; $i < 7; $i++) {
                foreach ($opening[$i] as $key => $value) {
                    if($start == $key){
                        foreach ($value as $date) {


                            if($start_hout >= $date->date_start && $start_hout <= $date->date_end){
                                $pass_start += 1;
                            }

                            if($end_hout >= $date->date_start && $end_hout <= $date->date_end){
                                $pass_end += 1;
                            }
                        }


                    }
                }
            }
            if($pass_start > 0 || $pass_end > 0){
                array_push($employees_array, $employee->id);
            }
            if(count($employees_array) > 0){
                return $employees_array;
            }else{
                return 0;
            }

        }



    }
}
