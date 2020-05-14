<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
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

        for ($i=0; $i < 7; $i++) {
            foreach ($opening[$i] as $key => $value) {
                if($start == $key){
                    foreach ($value as $date) {

                        if($start_hout >= $date->date_start && $start_hout <= $date->date_end){
                           $pass_start = 1;
                        }

                        if($end_hout >= $date->date_end && $end_hout <= $date->date_end){
                            $pass_end = 1;
                         }
                    }

                }
            }
        }

        if(!$pass_start){
            return 'La fecha de inicio no se encuentra disponible para las horas habiles.';
        }else if(!$pass_end){
            return 'La fecha final no se encuentra disponible para las horas habiles.';
        }
        return 1;

    }
}
