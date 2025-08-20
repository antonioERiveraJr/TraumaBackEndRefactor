<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log as FacadesLog;

class GeneralServicesContoller extends Controller
{
    use HttpResponses;


    public function locations(Request $r)
    {
        // dd('test');
        // check if $r is in cache

        // Cache::remember('locations', 3600, function () {
        //     // return  DB::select("select * from registry.dbo.vwRegionProvinceCityBarangay2");
        //     return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
        //         ->select('*')
        //         ->get();
        // });
        // dd($result);


        // return $result;

        // if (Cache::has('locations')) {
        //     $locations = Cache::get('locations');
        //     return $locations;
        // }


        // $result = DB::select("select * from registry.dbo.vwRegionProvinceCityBarangay2");
        $result = Cache::remember('locations', 3600, function () {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('*')
                ->get();
        });
        // Cache::put('locations', $result, 3600);
        return $result;
    }


    public function newlocations(Request $r)
    {
        $result = Cache::remember('newlocations', 3600, function () {
            return DB::select('exec registry.dbo.getLocations');
        });
        return $result;
    }




    //implement function to get unique RegionCode and RegionDesc for locations




    // implement function get ProvinceCode and ProvinceDesc for locations then filter by Region

    //convert to DB:table ORM "select distinct provcode, provname from registry.dbo.vwRegionProvinceCityBarangay2 where regcode = '01'"

    public function provinceByRegion(Request $r)
    {
        $regionCode = $r->regcode;
        // dd($regionCode);
        // FacadesLog::info('regioncode: ',  [$regionCode]);
        $result = Cache::remember('provinceByRegion' . $regionCode, 3600, function () use ($regionCode) {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('provcode', 'provname')
                ->where('regcode', '=', $regionCode) // Adjusted line
                ->distinct()
                ->orderBy('provname', 'asc')
                ->get();

            dd($res);
        });

        return $result;
    }

    // public function provinceByRegion(Request $r)
    // { 
    //     $regionCode = $r->regcode; 

    //     // Log the incoming region code
    //     FacadesLog::info('Region Code: ', [$regionCode]);

    //     // Unique cache key
    //     $cacheKey = "provinceByRegion:{$regionCode}";

    //     $result = Cache::remember($cacheKey, 3600, function () use ($regionCode) {
    //         return DB::table('bgh_libraries.demographics.ref_province')
    //             ->select('old_provcode', 'old_provname', 'provname','provcode')
    //             ->where('provcode', 'LIKE', "{$regionCode}%")  
    //             ->distinct()
    //             ->orderBy('provname', 'asc')
    //             ->get();
    //     });

    //     // Log the fetched provinces
    //     // FacadesLog::info('Provinces fetched: ', [$result]);

    //     return $result;
    // }
    // public function provinceByRegion(Request $r)
    // {
    //     $regionCode = $r->regcode;

    //     // Unique cache key
    //     $cacheKey = "provinceByRegion:{$regionCode}";

    //     $result = Cache::remember($cacheKey, 3600, function () use ($regionCode) {
    //         return DB::table('bgh_libraries.demographics.ref_province')
    //             ->select(
    //                 DB::raw('COALESCE(old_provcode, provcode) as old_provcode'),
    //                 'old_provname',
    //                 'provname',
    //                 'provcode'
    //             )
    //             ->where('provcode', 'LIKE', "{$regionCode}%")
    //             ->distinct()
    //             ->orderBy('provname', 'asc')
    //             ->get();
    //     });

    //     // Log the fetched provinces
    //     FacadesLog::info('region: ', [$regionCode]);
    //     FacadesLog::info('province: ', [$result]);

    //     return $result;
    // }
    public function cityByProvince(Request $r)
    {

        // dd($regionCode);
        $provcode = $r->provcode;

        $result = Cache::remember('cityByProvince' . $provcode, 3600, function () use ($provcode) {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('ctycode', 'ctyname')
                ->where('provcode', '=', $provcode)
                ->distinct()
                ->orderBy('ctyname', 'asc')
                ->get();

            // dd($res);
        });

        return $result;
    }

    // public function cityByProvince(Request $r)
    // { 
    //     $provcode = $r->provcode;

    //     // Unique cache key
    //     $cacheKey = "cityByProvince:{$provcode}";

    //     $result = Cache::remember($cacheKey, 3600, function () use ($provcode) {
    //         return DB::table('bgh_libraries.demographics.ref_city')
    //             ->select('old_citycode', 'cityname')
    //             ->where('old_citycode', 'LIKE', "{$provcode}%")  
    //             ->distinct()
    //             ->orderBy('cityname', 'asc')
    //             ->get();
    //     });

    //     // Log the result for debugging
    //     // FacadesLog::info('Cities fetched: ', [$result]);

    //     return $result;
    // }


    public function bgyByCity(Request $r)
    {

        // dd($regionCode);
        $ctycode = $r->ctycode;

        $result = Cache::remember('bgyByCity' . $ctycode, 3600, function () use ($ctycode) {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('bgycode', 'bgyname')
                ->where('ctycode', '=', $ctycode)
                ->distinct()
                ->orderBy('bgyname', 'asc')
                ->get();

            // dd($res);
        });

        return $result;
    }

    // public function bgyByCity(Request $r)
    // {
    //     $ctycode = $r->ctycode;

    //     // Use a unique cache key
    //     $cacheKey = "bgyByCity:{$ctycode}";

    //     $result = Cache::remember($cacheKey, 3600, function () use ($ctycode) {
    //         return DB::table('bgh_libraries.demographics.ref_barangay')
    //             ->select('old_bgycode', 'bgyname')
    //             ->where('old_bgycode', 'LIKE', "{$ctycode}%") // Improved readability
    //             ->distinct()
    //             ->orderBy('bgyname', 'asc')
    //             ->get();
    //     });

    //     // Log the result for debugging
    //     FacadesLog::info('Barangays fetched: ', [$result]);

    //     return $result;
    // }
    //implement function to get unique regcode and regname from registry.dbo.vwRegionProvinceCityBarangay2
    public function regions(Request $r)
    {

        // dd($regionCode);
        // $ctycode = $r->ctycode;
        // Cache::forget('regions');
        $result = Cache::remember('regions', 3600, function () {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('regcode', 'regname')
                ->distinct()
                ->orderBy('regname', 'asc')
                ->get();

            // dd($res);
        });

        return $result;
    }
    //sample code to implement provinceByRegion
    // public function provinceByRegion(Request $r, $regionCode)


    // implement function get results for locationsByRegion then filter by Province

    // public function regions(Request $r)
    // {
    //     // Use the specified connection for the query
    //     $result = Cache::remember('regions', 3600, function () {
    //         return DB::table('bgh_libraries.demographics.ref_region') 
    //             ->select('old_regcode', 'regname')
    //             ->distinct()
    //             ->orderBy('regname', 'asc')
    //             ->get();
    //     });

    //     FacadesLog::info('regions: ', [$result]);
    //     // dd($result);
    //     return $result;
    // }

    public function provinceByRegionDesc(Request $r)
    {
        $result = Cache::remember('provinceByRegionDesc', 3600, function () {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('provcode, provcode', 'provname')
                ->distinct()
                ->orderBy('provname', 'asc')
                ->get();

            // dd($res);
        });

        return $result;
    }

    public function citiesByProvinceDesc(Request $r)
    {
        $result = Cache::remember('citiesByProvinceDes', 3600, function () {
            return DB::table('registry.dbo.vwRegionProvinceCityBarangay2')
                ->select('ctycode', 'ctyname')
                ->distinct()
                ->orderBy('ctyname', 'asc')
                ->get();

            // dd($res);
        });

        return $result;
    }



    public function searchPatient(request $r)
    {
        // dd($r->hpercode);

        $params = [
            $r->hpercode,
            $r->patlast,
            $r->patfirst,
            $r->patmiddle
        ];

        try {

            $result = DB::select('exec hospapi.dbo.sp_search_patients ?, ?, ?, ?', $params);

            return response()->json($result);
        } catch (QueryException $e) {
            // Log the error
            // Log::error('Database error: ' . $e->getMessage());

            // Return a JSON response indicating an error
            return response()->json([
                'success' => false,
                'message' => 'A database error occurred.',
                'server error' => $e->getMessage()
            ], 500);
        }

        // dd($params);

        // if ($r->hpercode || $r->patlast || $r->patfirst || $r->patmiddle) {

        //return APIUtilities::staticQuery('exec hospapi.dbo.sp_search_patients ?, ?, ?, ?', $params, 'Patient');

    }
}
