<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MLServicesController extends Controller
{
    public function getKeyTerms(Request $r)
    {
        $keyTerms = DB::connection('sqlsrv66')->select('exec registry.library.extractKeyTerms ?', [$r->text]);

        return response()->json($keyTerms);
    }

    public static function getNoiToiPoiDoi(Request $r)
    {
        $noiToiPoiDoi = DB::connection('sqlsrv66')->select('exec registry.library.getNoiToiPoiDoi ?', [$r->text]);

        return response()->json($noiToiPoiDoi);
    }
}
