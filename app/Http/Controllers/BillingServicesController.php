<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BillingServicesController extends Controller
{
    //
    public function billStatusList(request $r){
        
        $result = DB::table('hospital.billing.vwBillStatusList as vw')
        ->orderByRaw("case when billstatuscode = 'SUBL' then '1' 
                           when billstatuscode = 'FCER' then '2' 
                           when billstatuscode is null then '4' 
                           else '3' end")

        ->get();

        // $result = json_decode($result[0]->data);

        return response()->json($result);
    }
}
