<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\DB;
// use App\Traits\HttpResponses;
// use stdClass;
// use Illuminate\Support\Facades\Cache;
// use Illuminate\Support\Facades\Response;
// use Termwind\Components\Dd;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Traits\HttpResponses;
use stdClass;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Termwind\Components\Dd;
use ZipArchive;
use Illuminate\Support\Collection;
use League\Csv\Writer;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Clockwork\Support\Laravel\Facade;
use Exception;
use Illuminate\Support\Facades\Log as FacadesLog;
use Illuminate\Support\Log;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InjuryExport;
use PDF; // Import the PDF facade
use Dompdf\Dompdf;
use Dompdf\Options;
// use Dompdf\Dompdf;
// use Dompdf\Options; 
// use Illuminate\Container\Attributes\Log;

class InjuryServicesController extends Controller

{
    use HttpResponses;
    // public function injuryList(Request $r)
    // {

    //     if ($r->hardRefresh == null) {
    //         $r->hardRefresh = false;
    //     }

    //     if ($r->hardRefresh) {
    //         // dd('hard refresh');
    //         Cache::forget('injuryList');
    //     }

    //     // dd('cached');
    //     if (Cache::has('injuryList')) {
    //         $result = Cache::get('injuryList');
    //         return $result;
    //     } else {

    //         // Cache::forget('injuryList');
    //         // $result = DB::select('select * from registry.injury.vwInjuryPatient');
    //         // $result = DB::table('registry.injury.vwInjuryPatient')
    //         //     ->select('enccode', 'header', 'status', 'details')
    //         //     ->get();

    //             $result = DB::table('registry.injury.vwInjuryPatient')
    //             ->select('enccode', 'header', 'status', 'details', 'admdate')
    //             // ->limit(100)
    //             ->whereDate('admdate', '>=', '01/21/2024')
    //             ->whereDate('admdate', '<=', '01/28/2024')
    //             ->where('status', $r->status)
    //             ->orderByDesc('admdate')
    //             ->get();
    //             // ->paginate (10); 


    //         foreach ($result as $res) {
    //             $res->header = json_decode($res->header)[0];
    //             $res->details = json_decode($res->details);
    //         }

    //         Cache::put('injuryList', $result, 3600);

    //         return  $result;
    //     }
    // }

    // public function injuryList(Request $r)
    // {
    //     // Set default value for hardRefresh if not provided
    //     if ($r->hardRefresh == null) {
    //         $r->hardRefresh = false;
    //     }

    //     // Handle hard refresh
    //     if ($r->hardRefresh) {
    //         Cache::forget('injuryList');
    //     }

    //     // Check if cached data exists
    //     if (Cache::has('injuryList')) {
    //         $result = Cache::get('injuryList');
    //         return $result;
    //     } else {
    //         // Initialize the query

    //         if (is_null($r->startdate) && is_null($r->enddate)) {
    //             // Set default dates
    //             $startDate = '01/21/2024';
    //             $endDate = '01/28/2024';
    //         } else {
    //             $startDate = $r->startdate;
    //             $endDate = $r->enddate;
    //         }
    //         FacadesLog::info('Injury List Request Dates:', [
    //             'startdate' => $startDate,
    //             'enddate' => $endDate,
    //         ]);
    //         $result = DB::table('registry.injury.vwInjuryPatient')
    //                     ->select('enccode', 'header', 'status', 'details', 'admdate')
    //                     // // ->limit(100)


    //                     // Use the dates in the whereDate clauses
    //                     ->whereDate('admdate', '>=', $startDate)
    //                     ->whereDate('admdate', '<=', $endDate)
    //                     ->where('status', $r->status)
    //                     ->orderByDesc('admdate')
    //                     ->get();
    //         // Use provided startdate and enddate, or set default values
    //         // if ($r->startdate) {
    //         //     $query->
    //         //     Log::debug('Updated  date value: ' . $endDate);


    //         // }else{
    //         //     $query->whereDate('admdate', '>=', '01/21/2024');
    //         // }

    //         // if ($r->enddate) {

    //         //     Log::debug('Updated end date value: ' . $endDate);
    //         // }else{
    //         //     $query->whereDate('admdate', '<=', '01/28/2024');
    //         // }


    //         // if ($r->status) {
    //         //     $query->where('status', $r->status);
    //         // }

    //         // // Execute the query
    //         // $result = $query->get();

    //         // Decode JSON fields
    //         foreach ($result as $res) {
    //             $res->header = json_decode($res->header)[0];
    //             $res->details = json_decode($res->details);
    //         }

    //         // Cache the result for 1 hour
    //         Cache::put('injuryList', $result, 3600);

    //         return $result;
    //     }
    // }

    //unfinished Doctors TSS Forms
    public function getUnfinishedTSSForms(REquest $r)
    {
        $result = DB::table('registry.injury.vwUnfinishedTSSList')
            ->select('*')
            ->get();
        return $result;
    }

    // OLD TRAUMA SURVEILLANCE SYSTEM (TSS)
    // public function injuryList3(Request $r)
    // {
    //     if ($r->hardRefresh == null) {
    //         $r->hardRefresh = false;
    //     }
    //     if ($r->hardRefresh) {
    //         Cache::forget('injuryList3');
    //     }
    //     if (Cache::has('injuryList3')) {
    //         $result = Cache::get('injuryList3');
    //         return $result;
    //     } else {
    //         if (is_null($r->startdate) && is_null($r->enddate)) {
    //             $startDate = now()->subDays(7)->format('m/d/Y');
    //             $endDate = now()->format('m/d/Y');
    //         } else {
    //             $startDate = $r->startdate;
    //             $endDate = $r->enddate;
    //         }
    //         $datedescription = $r->dateDescription;

    //         if ($r->dateDescription === 'Date of Consultation') {
    //             $datedescription = 'admdate';
    //         } else {
    //             $datedescription = 'injtme';
    //         }

    //         $result = DB::table('registry.injury.vwInjuryPatient')
    //             ->select('enccode', 'header', 'status', 'details', 'tStamp', 'admdate', 'injtme', 'primediag')
    //             ->whereDate($datedescription, '>=', $startDate)
    //             ->whereDate($datedescription, '<=', $endDate)
    //             // ->where('primediag', '=', 'Y')
    //             ->where('status', $r->status)
    //             // ->where('primediag', '=', null, 'and', 'primediag', '=', 'Y')
    //             ->where(function ($query) {
    //                 $query->where('primediag', '=', null)
    //                     ->orWhere('primediag', '=', 'Y');
    //             })
    //             ->orderByDesc($datedescription)
    //             // ->distinct()
    //             ->get();
    //         // FacadesLog::info('inj list: ', [$result]);
    //         $uniqueResults = $result->unique('enccode');
    //         foreach ($uniqueResults as $res) {
    //             $res->header = json_decode($res->header)[0];
    //             $res->details = json_decode($res->details);
    //         }
    //         Cache::put('injuryList', $uniqueResults, 3600);

    //         return $uniqueResults;
    //     }
    // }

    public function injuryList3(Request $r)
    {
        if ($r->hardRefresh == null) {
            $r->hardRefresh = false;
        }
        if ($r->hardRefresh) {
            Cache::forget('injuryList3');
        }
        if (Cache::has('injuryList3')) {
            return Cache::get('injuryList3');
        } else {
            if (is_null($r->startdate) && is_null($r->enddate)) {
                $startDate = now()->subDays(7)->format('m/d/Y');
                $endDate = now()->format('m/d/Y');
            } else {
                $startDate = $r->startdate;
                $endDate = $r->enddate;
            }
            $datedescription = $r->dateDescription;

            if ($r->dateDescription === 'Date of Consultation') {
                $datedescription = 'admdate';
            } else {
                $datedescription = 'injtme';
            }

            $result = DB::table('registry.injury.vwInjuryPatient')
                ->select('enccode', 'header', 'status', 'details', 'tStamp', 'admdate', 'injtme', 'primediag')
                ->whereDate($datedescription, '>=', $startDate)
                ->whereDate($datedescription, '<=', $endDate)
                ->where('status', $r->status)
                ->where(function ($query) {
                    $query->where('primediag', '=', null)
                        ->orWhere('primediag', '=', 'Y');
                })
                ->orderByDesc($datedescription)
                ->get();

            $uniqueResults = $result->unique('enccode')->values(); // Ensure it's indexed correctly

            $formattedResults = $uniqueResults->map(function ($res) {
                $res->header = json_decode($res->header)[0];
                $res->details = json_decode($res->details);
                return $res;
            })->toArray(); // Convert to array

            Cache::put('injuryList3', $formattedResults, 3600); // Cache the array

            return $formattedResults; // Return as array
        }
    }

    // exporting admitted patients List
    public function admittedInjuryList(Request $r)
    {

        $result = DB::table('registry.injury.vwAdmittedInjuryPatient')
            ->select('enccode', 'header', 'status', 'details', 'tStamp', 'admdate', 'injtme', 'primediag', 'archdate')
            // ->where('primediag', '=', 'Y')
            ->where('status', $r->status)
            // ->where('primediag', '=', null, 'and', 'primediag', '=', 'Y')
            ->where(function ($query) {
                $query->where('primediag', '=', null)
                    ->orWhere('primediag', '=', 'Y');
            })
            // ->distinct()
            ->get();
        // FacadesLog::info('inj list: ', [$result]);
        $uniqueResults = $result->unique('enccode');
        foreach ($uniqueResults as $res) {
            $res->header = json_decode($res->header)[0];
            $res->details = json_decode($res->details);
        }
        Cache::put('injuryList', $uniqueResults, 3600);

        return $uniqueResults;
    }
public function getABTCPhilhealthForm(Request $request)
{
    $hpercode = $request->input('Hpercode'); // Expecting an integer
    $empID = $request->input('empID'); // Expecting a string

    // Perform validation if necessary
    $request->validate([
        'Hpercode' => 'required|string',
        'empID' => 'required|string' // Ensure empID is treated as a string
    ]);

    // Call the stored procedure to get the data
    $data = DB::select('EXEC registry.dbo.getABTCPhilhealthForm ?, ?', [$hpercode, $empID]);

    // Return the data as a JSON response
    return response()->json($data);
}
    public function previewPDF(Request $request)
    {
        // If you need to fetch the data for preview
        $hpercode = $request->input('hpercode'); // Ensure this is passed in the request
        $empID = $request->input('empID');

        // Call the stored procedure to get the data
        $data = DB::select('EXEC registry.dbo.getABTCPhilhealthForm ?, ?', [$hpercode, $empID]);
        // Check if data is returned
        if (empty($data)) {
            return response()->json(['error' => 'No data found'], 404);
        }

        $formFields = (object) $request->formFields;

        // Prepare the view with the data
        return view('abtc_form', ['formData' => $data[0], 'formFields' => $formFields]);
    }

    public function generateABTCPdf(Request $request)
    {
        $hpercode = $request->input('Hpercode');
        $empID = $request->input('empID'); // Expecting a string

        $formFields = (object) $request->formFields;
        // Call the stored procedure to get the data
        $data = DB::select('EXEC registry.dbo.getABTCPhilhealthForm ?, ?', [$hpercode, $empID]);

        // Check if data is returned
        if (empty($data)) {
            return response()->json(['error' => 'No data found'], 404);
        }

        // Convert formFields array to an object 

        // Prepare the view with the data
        $pdf = Pdf::loadView('abtc_form', ['formData' => $data[0], 'formFields' => $formFields]);

        FacadesLog::info('Form Data:', [
            'data' => $data[0],
            'formField' => $formFields
        ]);

        return $pdf->stream('ABTC_Philhealth_Form.pdf');
    }

    // public function generatePDF(Request $request)
    // {
    //     $hpercode = $request->input('Hpercode');

    //     // Fetch your data here
    //     $data = DB::select('EXEC registry.dbo.getABTCPhilhealthForm ?', [$hpercode]);

    //     if (empty($data)) {
    //         return response()->json(['error' => 'No data found'], 404);
    //     }

    //     // Load the view and pass the data
    //     $html = view('abtc_form', ['formData' => $data[0]])->render();

    //     // Set up Dompdf options
    //     $options = new Options();
    //     $options->set('defaultFont', 'Arial');
    //     $options->set('isHtml5ParserEnabled', true);
    //     $options->set('isRemoteEnabled', true);

    //     $dompdf = new Dompdf($options);
    //     $dompdf->loadHtml($html);

    //     // Set paper size and orientation
    //     $dompdf->setPaper('A4', 'landscape');

    //     // Render the PDF
    //     $dompdf->render();

    //     // Output the generated PDF to Browser
    //     return response()->stream(
    //         function () use ($dompdf) {
    //             echo $dompdf->output();
    //         },
    //         200,
    //         [
    //             'Content-Type' => 'application/pdf',
    //             'Content-Disposition' => 'attachment; filename="ABTC_Philhealth_Form.pdf"',
    //         ]
    //     );
    // }
    // NEW TSS
    public function injuryList(Request $r)
    {
        // FacadesLog::info('Injury List Request:', [
        //     'startdate' => $r->startdate,
        //     'enddate' => $r->enddate,
        //     'status' => $r->status,
        //     'hardRefresh' => $r->hardRefresh,
        // ]);
        // Set default value for hardRefresh if not provided
        // if ($r->hardRefresh == null) {
        //     $r->hardRefresh = false;
        // }


        // Handle hard refresh
        if ($r->hardRefresh) {
            Cache::forget('injuryList');
        }

        // Check if cached data exists
        if (Cache::has('injuryList')) {
            $result = Cache::get('injuryList');
            return $result;
        } else {
            // Initialize the query
            if (is_null($r->startdate) && is_null($r->enddate)) {
                // Set default dates
                $startDate = now()->subDays(7)->format('m/d/Y');
                $endDate = now()->format('m/d/Y');
            } else {
                $startDate = $r->startdate;
                $endDate = $r->enddate;
            }

            // FacadesLog::info('Injury List Request Dates:', [00
            //     'startdate' => $startDate,
            //     'enddate' => $endDate,   
            // ]);
            $datedescription = $r->dateDescription;

            if ($r->dateDescription === 'Date of Consultation') {
                $datedescription = 'admdate';
            } else {
                $datedescription = 'injtme';
            }

            // FacadesLog::info('datedes: ', ['',$datedescription]);

            // Use distinct to avoid duplicates based on enccode **** used hencdiag_adm instead 

            $result = DB::table('registry.injury.injuryDataJSON')
                ->select('enccode', 'header', 'status', 'data', 'admdate', 'injtme', 'archdate', 'exportby')

                // ->whereDate($datedescription, '>=', $startDate)
                // ->whereDate($datedescription, '<=', $endDate)

                ->whereDate($datedescription, '>=', $startDate)
                ->whereDate($datedescription, '<=', $endDate)
                ->where('status', $r->status)
                // ->where('primediag', '=', 'Y')
                // ->where('primediag', '=', null, 'and', 'primediag', '=', 'Y')
                ->where(function ($query) {
                    $query->where('primediag', '=', null)
                        ->orWhere('primediag', '=', 'Y');
                })
                ->orderByDesc($datedescription)
                ->distinct()
                ->get();


            // Decode JSON fields and filter out duplicates


            $uniqueResults = $result->unique('enccode');

            // FacadesLog::info([], ['result' => $uniqueResults]);


            // // Log duplicates if any
            // if ($result->count() > $uniqueResults->count()) {
            //     $duplicates = $result->diff($uniqueResults);
            //     FacadesLog::warning('Duplicate entries found in injury list:', [
            //         'duplicates' => $duplicates->toArray(),
            //     ]);
            // }

            foreach ($uniqueResults as $res) {
                $res->header = json_decode($res->header)[0];
                $res->details = json_decode($res->data);
            }

            // Cache the result for 1 hour
            Cache::put('injuryList', $uniqueResults, 3600);
            return $uniqueResults;
        }
    }



    public function injuryListDev(Request $r)
    {
        // FacadesLog::info('Injury List Request:', [
        //     'startdate' => $r->startdate,
        //     'enddate' => $r->enddate,
        //     'status' => $r->status,
        //     'hardRefresh' => $r->hardRefresh,
        // ]);
        // Set default value for hardRefresh if not provided
        if ($r->hardRefresh == null) {
            $r->hardRefresh = false;
        }


        // Handle hard refresh
        if ($r->hardRefresh) {
            Cache::forget('injuryListDev');
        }

        // Check if cached data exists
        if (Cache::has('injuryListDev')) {
            $result = Cache::get('injuryListDev');
            return $result;
        } else {
            // Initialize the query
            if (is_null($r->startdate) && is_null($r->enddate)) {
                // Set default dates
                $startDate = now()->subDays(7)->format('m/d/Y');
                $endDate = now()->format('m/d/Y');
            } else {
                $startDate = $r->startdate;
                $endDate = $r->enddate;
            }

            // FacadesLog::info('Injury List Request Dates:', [
            //     'startdate' => $startDate,
            //     'enddate' => $endDate,
            // ]);
            $datedescription = $r->dateDescription;

            if ($r->dateDescription === 'Date of Consultation') {
                $datedescription = 'admdate';
            } else {
                $datedescription = 'injtme';
            }

            // FacadesLog::info('datedes: ', ['',$datedescription]);

            // Use distinct to avoid duplicates based on enccode **** used hencdiag_adm instead 
            $result = DB::table('registry.injury.vwInjuryPatient')
                ->select('enccode', 'header', 'status', 'details', 'admdate', 'injtme', 'admEnccode')
                ->whereDate($datedescription, '>=', $startDate)
                ->whereDate($datedescription, '<=', $endDate)
                ->where('status', $r->status)
                ->where('primediag', '=', 'Y')
                ->orderByDesc($datedescription)
                ->distinct()
                ->get();

            // Decode JSON fields and filter out duplicates
            $uniqueResults = $result->unique('enccode');

            // // Log duplicates if any
            // if ($result->count() > $uniqueResults->count()) {
            //     $duplicates = $result->diff($uniqueResults);
            //     FacadesLog::warning('Duplicate entries found in injury list:', [
            //         'duplicates' => $duplicates->toArray(),
            //     ]);
            // }

            foreach ($uniqueResults as $res) {
                $res->header = json_decode($res->header)[0];
                $res->details = json_decode($res->details);
            }

            // Cache the result for 1 hour
            Cache::put('injuryList', $uniqueResults, 3600);

            return $uniqueResults;
        }
    }


    public function  reportList(Request $r)
    {
        // Set default value for hardRefresh if not provided
        if ($r->hardRefresh == null) {
            $r->hardRefresh = false;
        }


        // Handle hard refresh
        if ($r->hardRefresh) {
            Cache::forget('reportList');
        }

        // Check if cached data exists
        if (Cache::has('reportList')) {
            $result = Cache::get('reportList');
            return $result;
        } else {
            // Initialize the query
            if (is_null($r->startdate) && is_null($r->enddate)) {
                // Set default dates
                $startDate = '01/21/2024';
                $endDate = '01/28/2024';
            } else {
                $startDate = $r->startdate;
                $endDate = $r->enddate;
            }

            // FacadesLog::info('Injury List Request Dates:', [
            //     'startdate' => $startDate,
            //     'enddate' => $endDate,
            // ]);
            $datedescription = $r->dateDescription;

            if ($r->dateDescription === 'Date of Consultation') {
                $datedescription = 'admdate';
            } else {
                $datedescription = 'injtme';
            }

            // FacadesLog::info('datedes: ', ['',$datedescription]);

            // Use distinct to avoid duplicates based on enccode
            // $result = DB::table('registry.injury.vwInjuryPatient')
            //     ->select('enccode', 'header', 'status', 'details', 'admdate', 'injtme')
            //     ->whereDate($datedescription, '>=', $startDate)
            //     ->whereDate($datedescription, '<=', $endDate)
            //     ->where('primediag', '=', 'Y')
            //     ->orderByDesc('admdate')
            //     ->distinct()
            //     ->get();

            $result = DB::table('registry.injury.injuryDataJSON')
                ->select('enccode', 'header', 'status', 'data', 'admdate', 'injtme')
                // ->where('status', $r->status)
                ->where('primediag', '=', null)
                ->whereDate('admdate', '>=', $startDate)
                ->whereDate('admdate', '<=', $endDate)
                ->where('status', '!=', null)
                ->whereNotNull('header')
                ->whereNotNull('data')
                ->orderByDesc('admdate')
                ->distinct()
                ->get();

            // Decode JSON fields and filter out duplicates
            $uniqueResults = $result->unique('enccode');

            // Log duplicates if any
            if ($result->count() > $uniqueResults->count()) {
                $duplicates = $result->diff($uniqueResults);
                // FacadesLog::warning('Duplicate entries found in injury list:', [
                //     'duplicates' => $duplicates->toArray(),
                // ]);
            }

            foreach ($uniqueResults as $res) {
                $res->header = json_decode($res->header)[0];
                $res->details = json_decode($res?->data);

                // FacadesLog::info('Decoded values:', [
                //     // 'header' => $res->header,
                //     'details' => $res->details,
                // ]);
            }


            // Cache the result for 1 hour
            Cache::put('reportList', $uniqueResults, 3600);

            return $uniqueResults;
        }
    }




    public function redirectToPatient(request $r)
    {



        return $r;
    }

    //DXDXDXDXDXDX database route
    public function injuryPatient(Request $r)
    {

        // $key = $r->enccode;
        // // check if $result is in cache
        // if (Cache::has($key)) {
        //     $result = Cache::get($key);
        //     return $result;
        // }
        // if not in cache, get from db 
        try {
            $result = DB::select("SELECT * from registry.injury.injuryDataJSON where enccode = '$r->enccode' and (primediag = 'Y' or primediag is null)");

            // $result = DB::select("select * from registry.injury.fnInjuryPatient('$r->enccode')");
            // $result = DB::table('registry.injury.vwInjuryPatient')
            //     ->select('enccode', 'header', 'details')
            //     ->where('enccode', $r->enccode)g
            //     ->get();

            // dd($result);

            if (count($result)) {
                // [0] -> alaem jai 1st element ta jai DB:select ket ireturn na as array
                $result = $result[0];
                $result->header = json_decode($result->header)[0];
                $result->details = json_decode($result->data);

                // Cache::put($key, $result, 60);
                return $result;
            } else {
                // [0] -> alaem jai 1st element ta jai DB:sele
                $result = DB::select("SELECT * from registry.injury.injuryDataJSON where enccode = '$r->enccode' and primediag = 'N'");
                $result = $result[0];
                $result->header = json_decode($result->header)[0];
                $result->details = json_decode($result->data);

                // Cache::put($key, $result, 60);
                return $result;
            }
        } catch (\Exception $e) {
            return $this->injuryPatientDevNoData($r);
        }
    }
    public function admittedInjuryListData(Request $r)
    {

        try {
            $result = DB::select("SELECT header,enccode,details from registry.injury.vwAdmittedInjuryPatient where enccode = '$r->enccode' and (primediag = 'Y' or primediag is null)");


            if (count($result)) {

                $result = $result[0];
                $result->header = json_decode($result->header)[0];
                $result->details = json_decode($result->details);

                return $result;
            }
        } catch (\Exception $e) {


            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    public function injuryPatientDev(Request $r)
    {
        // $key = $r->enccode;
        // // check if $result is in cache
        // if (Cache::has($key)) {
        //     $result = Cache::get($key);
        //     return $result;
        // }
        // if not in cache, get from db
        try {
            $result = DB::select("SELECT header,enccode,details from registry.injury.vwInjuryPatient where enccode = '$r->enccode' and (primediag = 'Y' or primediag is null)");

            // $result = DB::select("select * from registry.injury.fnInjuryPatient('$r->enccode')");
            // $result = DB::table('registry.injury.vwInjuryPatient')
            //     ->select('enccode', 'header', 'details')
            //     ->where('enccode', $r->enccode)g
            //     ->get();

            // dd($result);

            // if (!count($result)) {

            //     // [0] -> alaem jai 1st element ta jai DB:sele
            //     $result = DB::select("SELECT TOP 1 * from registry.injury.vwInjuryPatient where enccode = '$r->enccode' and primediag = 'N' ORDER BY tStamp DESC");
            //     $result = $result[0];
            //     $result->header = json_decode($result->header)[0];
            //     $result->details = json_decode($result->details);

            //     // Cache::put($key, $result, 60);
            //     return $result;
            // } else {
            //     // [0] -> alaem jai 1st element ta jai DB:select ket ireturn na as array
            //     $result = $result[0];
            //     $result->header = json_decode($result->header)[0];
            //     $result->details = json_decode($result->details);

            //     // Cache::put($key, $result, 60);
            //     return $result;
            // }

            if (count($result)) {

                // [0] -> alaem jai 1st element ta jai DB:sele
                $result = $result[0];
                $result->header = json_decode($result->header)[0];
                $result->details = json_decode($result->details);

                // Cache::put($key, $result, 60);
                return $result;
            }
        } catch (\Exception $e) {

            // return $this->injuryPatientDev($r);

            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    public function injuryPatientDevNoData(Request $r)
    {

        // $key = $r->enccode;
        // // check if $result is in cache
        // if (Cache::has($key)) {
        //     $result = Cache::get($key);
        //     return $result;
        // }
        // if not in cache, get from db
        try {
            // $result = DB::select("SELECT header,enccode,details from registry.injury.vwInjuryPatient where enccode = '$r->enccode' and (primediag = 'Y' or primediag is null)");
            $result = DB::select("SELECT header,enccode,details from registry.injury.vwInjuryPatientAll where enccode = '$r->enccode'");

            // $result = DB::select("select * from registry.injury.fnInjuryPatient('$r->enccode')");
            // $result = DB::table('registry.injury.vwInjuryPatient')
            //     ->select('enccode', 'header', 'details')
            //     ->where('enccode', $r->enccode)g
            //     ->get();

            // dd($result);

            // if (!count($result)) {

            //     // [0] -> alaem jai 1st element ta jai DB:sele
            //     $result = DB::select("SELECT TOP 1 * from registry.injury.vwInjuryPatient where enccode = '$r->enccode' and primediag = 'N' ORDER BY tStamp DESC");
            //     $result = $result[0];
            //     $result->header = json_decode($result->header)[0];
            //     $result->details = json_decode($result->details);

            //     // Cache::put($key, $result, 60);
            //     return $result;
            // } else {
            //     // [0] -> alaem jai 1st element ta jai DB:select ket ireturn na as array
            //     $result = $result[0];
            //     $result->header = json_decode($result->header)[0];
            //     $result->details = json_decode($result->details);

            //     // Cache::put($key, $result, 60);
            //     return $result;
            // }

            if (count($result)) {

                // [0] -> alaem jai 1st element ta jai DB:sele
                $result = $result[0];
                $result->header = json_decode($result->header)[0];
                $result->details = json_decode($result->details);

                // Cache::put($key, $result, 60);
                return $result;
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }


    // public function injuryPatientExport(Request $r)
    // {

    //     // $key = $r->enccode;
    //     // // check if $result is in cache
    //     // if (Cache::has($key)) {
    //     //     $result = Cache::get($key);
    //     //     return $result;
    //     // }
    //     // if not in cache, get from db
    //     $result = DB::select("select * from registry.injury.injuryDataJSON where enccode = '$r->enccode' and (primediag = 'Y' or primediag is null)");
    //     // $result = DB::select("select * from registry.injury.fnInjuryPatient('$r->enccode')");
    //     // $result = DB::table('registry.injury.vwInjuryPatient')
    //     //     ->select('enccode', 'header', 'details')
    //     //     ->where('enccode', $r->enccode)
    //     //     ->get();

    //     // dd($result);

    //     if (count($result) > 0) {
    //         // [0] -> alaem jai 1st element ta jai DB:select ket ireturn na as array


    //         $result = $result[0];
    //         $result->header = json_decode($result->header)[0];
    //         $result->details = json_decode($result->data);
    //         // FacadesLog::info('Exported Data:', ['data' => $result]);
    //         // Cache::put($key, $result, 60);
    //         return $result;
    //     }

    //     return $this->error($r->enccode, 'No record found', 500);
    // }

    public function injuryPatient2(Request $r)
    {

        $key = 'key' . $r->enccode;

        // check if $result is in cache
        // if (Cache::has($key)) {

        //     $result = Cache::get($key);
        //     return $result;
        // }

        // if not in cache, get from db
        // $result = DB::select("select * from registry.injury.vwInjuryPatient where enccode = '$r->enccode'");
        $result = DB::table('registry.injury.vwInjuryPatient')
            ->select('rowid', 'enccode', 'header', 'status', 'details')
            // ->select('*') // rowid, enccode, header, status, details
            ->where('enccode', $r->enccode)
            // ->where('status', 'drafts')
            ->get();
        // dd($key);
        // dd($result);

        if (count($result) > 0) {
            // [0] -> alaem jai 1st element ta jai DB:select ket ireturn na as array
            $result = $result[0];
            $result->header = json_decode($result->header)[0];
            $result->details = json_decode($result->details);

            // Cache::put($key, $result, 60);
            // dd(Cache::get($key));
            return $result;
        }

        // Cache::put($key, $result, 60);

        return $this->error($r->enccode, 'No record found', 500);
    }




    function exportToExcelSaveData(Request $r)
    {
        try {
            $enccodes = $r->enccode;
            if (!is_array($enccodes)) {
                return $this->error('enccode must be an array', 'Error', 400);
            }

            $results = [];
            foreach ($enccodes as $enccode) {
                $result = DB::table('registry.injury.injuryDataJSON')
                    ->where('enccode', '=', $enccode)
                    ->where(function ($query) {
                        $query->where('primediag', '=', null)
                            ->orWhere('primediag', '=', 'Y');
                    })
                    ->update(['status' => $r->status]);
                $results[] = $result;
            }

            if (array_sum($results) > 0) {
                return $this->success($results, 'Success', 200);
            } else {
                return $this->success([], 'No records updated', 200);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    // function saveData(Request $r)
    // {
    //     try {
    //         $result = DB::table('registry.injury.injuryDataJSON')
    //             ->insert($r->formattedData);


    //         if ($result) {
    //             return $this->success($result, 'Success', 200);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->error($e->getMessage(), 'Error', 500);
    //     }
    // }

    public function getAdmittedList(Request $r)
    {
        $result = [];
        try {
            $result = DB::select("exec registry.injury.GetInjuryPatientData ? ", [$r->status]);
            if (isset($result[0]) && isset($result[0]->enccode)) {
                $result[0]->status = 200;
                return $this->success($result, 'Success', 200);
            }
        } catch (Exception $e) {
            if (isset($result[0]) && isset($result[0]->invalidEnccode) && $result[0]->invalidEnccode) {
                return $this->error($result, 'Invalid enccode', 500);
            }
            if (isset($result[0]) && isset($result[0]->invalidJson) && $result[0]->invalidJson) {
                return $this->error($result, 'Invalid JSON', 500);
            }
            return $this->error($e->getMessage(), 'Error', 500);
        }
        return $result;
    }

    public function saveOPDData(Request $r)
    {
        $result = [];
        try {
            $result = DB::select("exec registry.dbo.saveOPDDataJSON ? ", [$r->formattedData]);
            if (isset($result[0]) && isset($result[0]->enccode)) {
                $result[0]->status = 200;
                return $this->success($result, 'Success', 200);
            }
        } catch (Exception $e) {
            if (isset($result[0]) && isset($result[0]->invalidEnccode) && $result[0]->invalidEnccode) {
                return $this->error($result, 'Invalid enccode', 500);
            }
            if (isset($result[0]) && isset($result[0]->invalidJson) && $result[0]->invalidJson) {
                return $this->error($result, 'Invalid JSON', 500);
            }
            return $this->error($e->getMessage(), 'Error', 500);
        }
        return $result;
    }
    function saveData(Request $r)
    {
        // dd($r->json);
        // $date = Carbon::now()->format('Y-m-d H:i:s.u');
        // FacadesLog::info('Current Date and Time:', ['date' => $date]);

        try {
            $result = DB::select("exec registry.injury.saveInjuryDataJSON ? ", [$r->formattedData]);
            if ($result[0]->enccode) {
                // Cache::forget('injuryList');
                // Cache::remember('injuryList', 3600, function () {
                //     return DB::table('registry.injury.vwInjuryPatient')
                //         ->select('enccode', 'header', 'status', 'details')

                //         ->get();
                // });

                $result[0]->status = 200;
                return $this->success($result, 'Success', 200);
            }
        } catch (\Exception $e) {

            if ($result[0]->invalidEnccode) {
                return $this->error($result, 'Invalid enccode', 500);
            }
            if ($result[0]->invalidJson) {

                return $this->error($result, 'Invalid JSON', 500);
            }
            return $this->error($e->getMessage(), 'Error', 500);
        }

        return $result;
    }

    // function insertDiagnosis(Request $r){
    //     try{
    //         $result = DB::table('hospital.dbo.hencdiag_adm')
    //         ->where('enccode', '=', $r->enccode)
    //         ->update([
    //             'diagtext' => $r->diagnosis
    //         ]); 

    //         if ($result) {
    //             return $this->success($result, 'Success', 200);
    //         }
    //     }  catch (\Exception $e) {
    //         return $this->error($e->getMessage(), 'Error', 500);
    //     }

    // }
    function updatePrimediag(Request $r)
    {
        try {

            // FacadesLog::info(['r: ',$r]);

            $insertResult = DB::table('hospital.dbo.hencdiag_adm')
                ->where('tstamp', '=', $r->tstamp)
                ->where('enccode', '=', $r->enccode)
                // ->where('diagtext', '=' , $r->diagtext)
                ->update(['primediag' => 'Y']);

            $updateResult = DB::table('hospital.dbo.hencdiag_adm')
                ->where('enccode', '=', $r->enccode)
                ->where('primediag', '=', 'Y')
                ->where('tstamp', '!=', $r->tstamp)
                ->update(['primediag' => 'N']);

            // Insert new diagnosis

            $updateResultDataJSON = DB::table('registry.injury.injuryDataJSON')
                ->where('enccode', '=', $r->enccode)
                // ->where('diagID', '!=' , $r->diagid)
                // ->where('tstamp', '!=' , $r->tstamp)
                // ->where('entryby', '=', $r->entryby)
                ->update(['primediag' => 'N']);

            $insertResultDataJSON = DB::table('registry.injury.injuryDataJSON')
                ->where('diagID', '=', $r->diagid)
                ->where('enccode', '=', $r->enccode)
                // ->where('diagtext', '=' , $r->diagtext)
                // ->where('entryby', '=', $r->entryby)
                ->update(['primediag' => 'Y']);






            if ($insertResult) {
                // FacadesLog::info([$updateResult,'ins: ', $insertResult]);
                return $this->success($insertResult, 'Success', 200);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    function insertBillingDiagnosis(Request $r)
    {
        try {
            $insertResult = DB::table('hospital.dbo.hencdiag')
                ->insert([
                    [
                        'enccode' => $r->enccode,
                        'hpercode' => $r->hpercode,
                        'primediag' => 'Y',
                        'licno' => DB::raw("(SELECT licno FROM hospital.dbo.hprovider WHERE employeeid = '$r->user')"),
                        'diagtext' => $r->diagnosis,
                        'entryby' => $r->user,
                        'edrem' => 'TSS',
                        'tdcode' => 'FINDX',
                        'edstat' => 'A',
                        'encdate' => now(),
                        'enctime' => now(),
                        'datemod' => now(),
                        'confdl' => 'N',
                    ]
                ]);
            if ($insertResult) {
                return $this->success($insertResult, 'Success', 200);
            }
        } catch (\Exception $e) {

            FacadesLog::info(['error inserting Billing', $e->getMessage()]);
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }
    function insertDiagnosis(Request $r)
    {
        // FacadesLog::info(['user', $r->user]);
        try {

            if ($r->isUpdateForm) {
                $updatetResult = DB::table('hospital.dbo.hencdiag_adm')
                    ->where('enccode', '=', $r->enccode)
                    ->where('primediag', '=', 'Y')
                    ->update([
                        'diagtext' => $r->diagnosis,
                        'updated_at' => now(),
                    ]);
            } else {
                // Update previous primary diagnosis to non-primary
                $updateResult = DB::table('hospital.dbo.hencdiag_adm')
                    ->where('enccode', '=', $r->enccode)
                    ->where('primediag', '=', 'Y')
                    ->update(['primediag' => 'N']);
                // Insert new diagnosis
                $insertResult = DB::table('hospital.dbo.hencdiag_adm')
                    ->insert([
                        'diagtext' => $r->diagnosis,
                        'enccode' => $r->enccode,
                        'entry_by' => $r->user,
                        'entryby' => $r->user,
                        'licno' => DB::raw("(SELECT licno FROM hospital.dbo.hprovider WHERE employeeid = '$r->user')"),
                        'primediag' => 'Y',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);




                if ($insertResult) {
                    return $this->success($insertResult, 'Success', 200);
                }
            }
        } catch (\Exception $e) {

            FacadesLog::info(['error', $e->getMessage()]);
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }


    function getEntryOfDoctors(Request $r)
    {
        try {
            $result = DB::table('hospital.dbo.hencdiag_adm')
                ->select('hospital.dbo.hencdiag_adm.enccode', 'hospital.dbo.hencdiag_adm.diagtext', 'hospital.dbo.hencdiag_adm.entryby', 'hospital.dbo.hencdiag_adm.tstamp', 'hospital.dbo.hencdiag_adm.id', 'hospital.dbo.hencdiag_adm.primediag')
                ->leftJoin('hospital.dbo.hpersonal as hp', 'hp.employeeid', '=', 'hospital.dbo.hencdiag_adm.entryby')
                ->addSelect('hp.firstname', 'hp.lastname', 'hp.empprefix')
                ->where('enccode', '=', $r->enccode)
                // ->where('entryby', '!=', $r->user)
                ->get();
            return response()->json($result);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    //nabaliktad ko ang objective and subjective 
    // public function insertObjSubj(Request $r)
    // {
    //     try { 
    //         FacadesLog::info('insertedAll:', [   'objective' => $r->objective,
    //         'finding' => $r->subjective,
    //         'hpercode' => $r->hpercode,
    //         'entry_by' => $r->entryby,
    //     'enccode' => $r->enccode,]);

    //         $result = DB::table('hospital.dbo.ufive_cli_finding')

    //             //dev
    //             // ->where('enccode', '=', $r->enccode)
    //             // ->update([
    //             //     'objective' => is_array($r->objective) ? implode(', ', $r->objective) : $r->objective,
    //             //     'finding' => $r->subjective,
    //             //     'hpercode' => $r->hpercode,
    //             //     'entry_by' => $r->entryby,
    //             // ]);

    //         //prod
    //             ->insert([ 
    //                 'enccode' => $r->enccode,
    //                 'objective' => is_array($r->objective) ? implode(', ', $r->objective) : $r->objective,
    //                 'finding' => $r->subjective,
    //                 'hpercode' => $r->hpercode,
    //                 'entry_by' => $r->entryby,
    //             ]);

    //         if ($result) {
    //             // return $this->success($result, 'Success', 200);

    //             return $this->success(['id' => $result], 'Success', 200);
    //         }
    //     } catch (Exception $e) {

    //         FacadesLog::info('error insert obj subj: ' . json_encode(['error' => $this->error($e->getMessage(), 'Error', 500)])); 
    //         return $this->error($e->getMessage(), 'Error', 500);
    //     }
    // }
    public function insertObjSubj(Request $r)
    {
        try {
            // FacadesLog::info('insertedAll:', [
            //     'objective' => $r->objective,
            //     'finding' => $r->subjective,
            //     'hpercode' => $r->hpercode,
            //     'entry_by' => $r->entryby,
            //     'enccode' => $r->enccode,
            //     'all' => $r->all
            // ]);
            // update when latest form and same ID
            if ($r->isUpdateForm) {
                $result = DB::table('hospital.dbo.ufive_cli_finding')
                    ->where('id', $r->ufiveID)
                    ->where('enccode', $r->enccode)
                    ->update([
                        'objective' => is_array($r->objective) ? implode(', ', $r->objective) : $r->objective,
                        'finding' => $r->subjective,
                        'hpercode' => $r->hpercode,
                        'entry_by' => $r->entryby,
                        'updated_at' => now()
                    ]);
            } else {
                $result = DB::table('hospital.dbo.ufive_cli_finding')->insertGetId([
                    'enccode' => $r->enccode,
                    'objective' => is_array($r->objective) ? implode(', ', $r->objective) : $r->objective,
                    'finding' => $r->subjective,
                    'hpercode' => $r->hpercode,
                    'entry_by' => $r->entryby,
                ]);
            }

            if ($result) {
                return $this->success(['id' => $result], 'Success', 200);
            }
        } catch (Exception $e) {
            FacadesLog::info('error insert obj subj: ' . json_encode(['error' => $this->error($e->getMessage(), 'Error', 500)]));
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }
    public function insertPlan(Request $r)
    {
        try {
            // update when latest form and same ID
            if ($r->isUpdateForm) {
                $result = DB::table('hospital.dbo.ufive_cli_plan')
                    ->where('id', $r->ufiveID)
                    ->where('enccode', $r->enccode)
                    ->update([
                        'pplan' => $r->plan,
                        'entry_by' => $r->entryby,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                $result = DB::table('hospital.dbo.ufive_cli_plan')->insertGetId([
                    'enccode' => $r->enccode,
                    'pplan' => $r->plan,
                    'hpercode' => $r->hpercode,
                    'entry_by' => $r->entryby,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'order_at' => now()
                ]);
            }

            if ($result) {
                return $this->success(['id' => $result], 'Success', 200);
            }
        } catch (Exception $e) {
            FacadesLog::info('error insert obj subj: ' . json_encode(['error' => $this->error($e->getMessage(), 'Error', 500)]));
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }
    public function insertChiefComplaint(Request $r)
    {
        try {
            // update when latest form and same ID
            if ($r->isUpdateForm) {
                $result = DB::table('hospital.les.cf4ChiefComplaint')
                    ->where('id', $r->ufiveID)
                    ->where('enccode', $r->enccode)
                    ->update([
                        'chief_complaint' => $r->plan,
                        'entry_by' => $r->entryby,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
            } else {
                $result = DB::table('hospital.les.cf4ChiefComplaint')->insertGetId([
                    'enccode' => $r->enccode,
                    'chief_complaint' => $r->plan,
                    'hpercode' => $r->hpercode,
                    'entry_by' => $r->entryby,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            if ($result) {
                return $this->success(['id' => $result], 'Success', 200);
            }
        } catch (Exception $e) {
            FacadesLog::info('error insert obj subj: ' . json_encode(['error' => $this->error($e->getMessage(), 'Error', 500)]));
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    // public function insertObjSubj(Request $r)
    // {
    //     try { 
    //         FacadesLog::info('insertedAll:', [
    //             'objective' => $r->objective,
    //             'finding' => $r->subjective,
    //             'hpercode' => $r->hpercode,
    //             'entry_by' => $r->entryby,
    //             'enccode' => $r->enccode,
    //         ]);

    //         $result = DB::table('hospital.dbo.ufive_cli_finding')->insertGetId([ 
    //             'enccode' => $r->enccode,
    //             'objective' => is_array($r->objective) ? implode(', ', $r->objective) : $r->objective,
    //             'finding' => $r->subjective,
    //             'hpercode' => $r->hpercode,
    //             'entry_by' => $r->entryby,
    //         ]);

    //         if ($result) {
    //             return $this->success(['id' => $result], 'Success', 200);
    //         }
    //     } catch (Exception $e) {
    //         FacadesLog::info('error insert obj subj: ' . json_encode(['error' => $this->error($e->getMessage(), 'Error', 500)])); 
    //         return $this->error($e->getMessage(), 'Error', 500);
    //     }
    // }
    function newCase(Request $r)
    {
        try {
            $result = DB::table('registry.dbo.opdDataJSON')
                ->where('hpercode', '=', $r->hpercode)
                ->where('lockCase', '=', null)
                ->update([
                    'lockCase' => now()
                ]);

            if ($result) {
                return $this->success($result, 'Success', 200);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    function updateMedilogs(Request $r)
    {
        try {
            $result = DB::table('hospital.dbo.hecase')
                ->where('enccode', '=', $r->enccode)
                ->update([
                    'ijntme' => $r->injtme,
                    'injrem' => $r->injrem,
                    'injloc' => $r->injloc,
                    'iicode' => $r->iicode,
                ]);

            if ($result) {
                return $this->success($result, 'Success', 200);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }
    function updateStatusForArchived(Request $r)
    {
        try {
            $enccodes = $r->enccode;
            if (!is_array($enccodes)) {
                return $this->error('enccode must be an array', 'Error', 400);
            }

            $results = [];
            foreach ($enccodes as $enccode) {
                $result = DB::table('registry.injury.injuryDataJSON')
                    ->where('enccode', '=', $enccode)
                    ->where('primediag', '=', 'Y')
                    ->update([
                        'archdate' => $r->archDate,
                        'exportby' => $r->exportby
                    ]);
                $results[] = $result;
            }

            if (array_sum($results) > 0) {
                return $this->success($results, 'Success', 200);
            } else {
                return $this->success([], 'No records updated', 200);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    function removeFromRegistry(Request $r)
    {

        try {
            DB::table('hospital.dbo.hecase')
                ->where('enccode', '=', $r->enccode)
                ->update(['iistat' => 'I']);

            Cache::forget('injuryList');
            return  $this->success($r->enccode, 'Success', 200);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    // public function getEmployeeName(Request $r)
    // {
    //     $employeeIds = $r->input('employeeids');

    //     if (!is_array($employeeIds)) {
    //         return response()->json(['message' => 'Invalid input: employeeids must be an array'], 400);
    //     }

    //     $employeeData = [];

    //     foreach ($employeeIds as $employeeId) {
    //         $result = DB::select("SELECT firstname, lastname FROM hospital.dbo.hpersonal WHERE employeeid = ?", [$employeeId]);

    //         if (count($result) > 0) {
    //             $employeeData[] = [
    //                 'employeeid' => $employeeId,
    //                 'firstname' => $result[0]->firstname,
    //                 'lastname' => $result[0]->lastname
    //             ];
    //         } else {
    //             $employeeData[] = [
    //                 'employeeid' => $employeeId,
    //                 'firstname' => null,
    //                 'lastname' => null
    //             ];
    //         }
    //     }

    //     return response()->json($employeeData);
    // }

    public function getFormDetail(Request $r)
    {
        try {
            $result = DB::table('registry.injury.injuryDataJSON')
                ->select('enccode', 'tStamp', 'diagID', 'primediag')
                ->where('enccode', $r->enccode)
                ->where('primediag', '=', 'Y')
                ->get();

            if ($result->isNotEmpty()) {
                return response()->json($result);
            } else {
                // FacadesLog::info('No record found for enccode:', ['enccode' => $r->enccode]);
                return response()->json([]);
            }
        } catch (\Exception $e) {
            // FacadesLog::info('Error fetching form detail:', ['error' => $e->getMessage()]);
            return response()->json([]);
        }
    }
    public function getListOfDiagnosis(Request $r)
    {
        try {
            $result = DB::table('hospital.dbo.hencdiag_adm')
                ->select('diagtext', 'tstamp')
                ->where('enccode', $r->enccode)
                ->get();
            return $result;
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    public function getListOfFinalDiagnosis(Request $r)
    {
        try {
            $result = DB::table('hospital.dbo.hencdiag')
                ->select('diagtext', 'enctime', 'tdcode')
                ->where('enccode', $r->admEnccode)
                ->where('tdcode', '=', 'FINDX')
                ->get();
            return $result;
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
    // public function getFormDetail(Request $r)
    // {
    //     // FacadesLog::info(['Form: ', $r->enccode]);
    //     try {
    //         $result = DB::table('registry.injury.injuryDataJSON')
    //             ->select('enccode', 'tStamp', 'diagID', 'primediag')
    //             ->where('enccode', $r->enccode)
    //             ->where('primediag', '=', 'Y')
    //             ->get();

    //         if ($result->isNotEmpty()) {
    //             return response()->json($result);
    //         } else {
    //             return $this->error($r->enccode, 'No record found', 404);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->error($e->getMessage(), 'Error', 500);
    //     }
    // }
    public function updateDislocationFields(Request $r)
    {
        try {
            $result = DB::table('registry.injury.injuryDataJSON')
                ->where('enccode', '=', $r->enccode)
                ->update([
                    'noi_disl_open' => $r->noi_disl_open,
                    'noi_disl_open_sp' => $r->noi_disl_open_sp,
                    'noi_disl_close' => $r->noi_disl_close,
                    'noi_disl_close_sp' => $r->noi_disl_close_sp,
                ]);

            if ($result) {
                return $this->success($result, 'Success', 200);
            } else {
                return $this->error('No records updated', 'Error', 400);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    public function getLatestEntryOfDoctors(Request $r)
    {
        try {
            $result = DB::table('hospital.dbo.hencdiag_adm')
                ->select('enccode', 'entryby', 'tStamp', 'id')
                ->where('enccode', $r->enccode)
                ->orderByDesc('tstamp')
                ->first();

            if ($result) {
                return response()->json($result);
            } else {
                return $this->error($r->enccode, 'No record found', 404);
            }
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), 'Error', 500);
        }
    }
    public function isOPDABTCFormUpdatable(Request $r)
    {
        // try {
            // Get the latest entry by hpercode
            $latestEntry = DB::table('registry.dbo.opdDataJSON')
                ->select('enccode', 'hpercode', 'entryby', 'tStamp', 'vaccineday')
                ->where('hpercode', $r->hpercode)
                ->where('lockCase', null)
                ->where('tStamp', '>=', now()->subHours(28))
                ->orderByDesc('tStamp')
                ->first();

            // Check if the latest entry exists and if the enccode matches
            // if ($latestEntry && $latestEntry->enccode === $r->enccode) {
            //     return response()->json($latestEntry);
            if ($latestEntry && strtolower($latestEntry->enccode) === strtolower($r->enccode)) {
                return response()->json($latestEntry);
            } else {
                return null;
            }
        // } catch (\Exception $e) {
        //     return $this->error($e->getMessage(), 'Error', 500);
        // }
    }

    //  public function getLatestDiagnosis(Request $r)
    // {
    //     try {
    //         $result = DB::table('hospital.dbo.hencdiag_adm')
    //             ->select('enccode', 'entryby', 'diagtext', 'tstamp')
    //             ->where('enccode', $r->enccode)
    //             ->where('entryby',$r->entryby )
    //             ->orderByDesc('tstamp')
    //             ->first();

    //         if ($result) {
    //             return response()->json($result);
    //         } else {
    //             return $this->error($r->enccode, 'No record found', 404);
    //         }
    //     } catch (\Exception $e) {
    //         return $this->error($e->getMessage(), 'Error', 500);
    //     }
    // }

    public function getPatientReferral(Request $r)
    {
        try {
            // Step 1: Get enccode from query parameters
            $enccode = $r->query('enccode');

            // Log the enccode for debugging

            // Step 2: Check if enccode exists in registry.injury.vwInjuryPatient
            // $exists = DB::table('registry.injury.injuryDataJSON')
            //     ->where('enccode', $enccode)
            //     ->where('primediag', '!=', 'N', 'or', 'primediag', '=', null)
            //     ->exists();

            // if (!$exists) {
            //     FacadesLog::error('enccode not found in registry.injury.injuryDataJSON.', ['enccode' => $enccode]);
            //     // return $this->error($enccode, 'enccode not found in registry.injury.vwInjuryPatient.', 404);
            // }

            // Step 3: Use the enccode to join ors.dbo.ors_patlog and ors.dbo.ors_patients
            $result = DB::table('ors.dbo.ors_patlog as patlog')
                ->leftJoin('ors.dbo.ors_patients as pat', 'patlog.hpercode', '=', 'pat.hpercode')
                ->select(
                    'patlog.hpercode',
                    'patlog.enccode',
                    'pat.patfirst',
                    'pat.patmiddle',
                    'pat.patlast',
                    'pat.initiatingFacility',
                    'pat.referredByName'
                )
                ->where('patlog.enccode', $enccode)
                ->orderByDesc('patlog.created_at')
                ->get();

            // if ($result->isEmpty()) {
            //     FacadesLog::error('No records found for the given enccode.', ['enccode' => $enccode]);
            // }

            return $this->success($result, 'Success', 200);
        } catch (\Exception $e) {
            // return $this->error($e->getMessage(), 'Error', 500);
        }
    }

    // public function checkPatientTSSRecord(Request $r)
    // {
    //     $patientsData = DB::table('registry.dbo.opdDataJSON')
    //         ->select('data', 'vaccineday')
    //         ->where('hpercode', '=', $r->hpercode)
    //         ->get();

    //     return $patientsData;
    // }

    //     public function checkPatientTSSRecord(Request $r)
    // { 
    //     $patientsData = DB::table('registry.dbo.opdDataJSON')
    //         ->select('data', 'vaccineday', 'tStamp', 'primeTSS')
    //         ->where('hpercode', '=', $r->hpercode)
    //         ->get();

    //     $decodedData = [];

    //     foreach ($patientsData as $patient) {
    //         // Decode the JSON data
    //         $decodedData[] = [
    //             'vaccineday' => $patient->vaccineday,
    //             'data' => json_decode($patient->data),
    //             'tStamp' => $patient->tStamp,
    //             'primeTSS' => $patient->primeTSS
    //         ];
    //     }

    //     return $decodedData;
    // }
    public function checkPatientTSSRecord(Request $r)
    {
        $patientsData = DB::table('registry.dbo.opdDataJSON')
            ->select('data', 'vaccineday', 'tStamp', 'primeTSS', 'prophylaxis')
            ->where('hpercode', '=', $r->hpercode)
            ->where('lockCase', '=', null)
            // ->where('prophylaxis', '=', $r->prophylaxis)
            // ->orderBy('tStamp')

            ->orderByDesc('tStamp')
            ->distinct()
            ->get();

        $decodedData = [];

        foreach ($patientsData as $patient) {
            // Decode the JSON data and format the tStamp
            $decodedData[] = [
                'vaccineday' => $patient->vaccineday,
                'data' => json_decode($patient->data),
                'tStamp' => Carbon::parse($patient->tStamp)->format('Y-m-d'), // Format to 'YYYY-MM-DD'
                'primeTSS' => $patient->primeTSS,
                'prophylaxis' => $patient->prophylaxis
            ];
        }

        return $decodedData;
    }

    public function getEmployeeName(Request $r)
    {
        $employeeIds = $r->input('employeeids');

        if (!is_array($employeeIds)) {
            return response()->json(['message' => 'Invalid input: employeeids must be an array'], 400);
        }

        $employeeData = DB::table('hospital.dbo.hpersonal')
            ->whereIn('employeeid', $employeeIds)
            ->select('employeeid', 'firstname', 'lastname')
            ->get();

        return response()->json($employeeData);
    }

    public function getSubjective(Request $r)
    {

        $response = DB::table('hospital.dbo.ufive_cli_finding as ufc')
            ->select('ufc.enccode', 'ufc.finding')
            ->join('hospital.dbo.hecase as hc', 'hc.enccode', '=', 'ufc.enccode')
            ->where('ufc.enccode', $r->enccode)
            ->get();


        // $finding = $response[0]->finding;
        //create variable $finding as new http request
        $finding = new Request(
            [
                'text' => $response[0]->finding
            ]
        );

        //add \App\Http\Controllers\MLServicesController::getNoiToiPoiDoi($finding) to $response
        $response[0]->noiToiPoiDoi = json_decode(\App\Http\Controllers\MLServicesController::getNoiToiPoiDoi($finding)->content());



        // return $finding;
        return $response;
    }


    // public function opdPatientData(Request $r)
    // { 
    //     // dd($r->enccode);
    //     $result = DB::select('exec registry.injury.GetInjuryPatientByEnccode ?', [$r->enccode]);

    //     return response()->json($result[0]);
    // }

    public function getPatientABTCLog(Request $r)
    {
        $response = DB::table('registry.dbo.opdDataJSON')
            ->select('*')
            ->where('hpercode', '=', $r->hpercode)
            ->where('lockCase', '!=', null)
            ->orderByDesc('tstamp')
            ->get();
        return $response;
    }

    public function opdPatientData(Request $r)
    {
        $result = DB::select('exec registry.injury.GetInjuryPatientByEnccode ?', [$r->enccode]);

        if (empty($result)) {
            return response()->json(['message' => 'No data found'], 404);
        }

        $decodedData = json_decode($result[0]->data);

        $responseData = [
            'patientname' => $result[0]->patientname,
            'hpercode' => $result[0]->hpercode,
            'enccode' => $result[0]->enccode,
            'opdtime' => $result[0]->opdtime,
            'patientbirthdate' => $result[0]->patientbirthdate,
            'data' => $decodedData,
            'vaccineday' => $result[0]->vaccineday,
            'lockCase' => $result[0]->lockCase
        ];

        return response()->json($responseData);
    }

    public function getSubjectiveNoiToiPoiDoi(Request $r)
    {


        $result = DB::select('exec registry.injury.getSubjectiveObjective ?', [$r->enccode])[0];

        return response()->json($result);
    }

    public function getKeyTerms(Request $r)
    {

        // $result = DB::connection('sqlsrv66')->select('exec registry.library.extractKeyTerms ?', [$r->text]);

        $result = DB::select('exec mlserver.registry.library.extractKeyTerms ?', [$r->text]);
        // dd($result);
        return response()->json($result);
    }

    public function extractNOIPOIDOITOIMOI(Request $request)
    {
        $text = $request->text;
        $keywords = ['NOI', 'TOI', 'DOI', 'POI', 'MOI'];
        $result = [];

        foreach ($keywords as $keyword) {
            $pos = strpos($text, $keyword);
            if ($pos !== false) {
                $substring = substr($text, $pos);
                $exploded = explode("\n", $substring, 2);
                $value = explode(':', $exploded[0], 2)[1] ?? null;
                $result[strtolower($keyword)] = trim($value);
            } else {
                $result[strtolower($keyword)] = null;
            }
        }

        $pattern = '/(DOI|TOI|POI|NOI|MOI):\s*[^\n]*\n?/';
        $details = rtrim(ltrim(preg_replace($pattern, '', $text)));
        // $result['details'] = $details;
        $result['details'] = $result['noi'] || $result['poi'] || $result['doi'] || $result['toi'] || $result['moi'] ? $details : null;
        $result['noi'] = $result['noi'] ?? $result['moi'] ?? null;
        $result['moi'] = $result['noi'] ?? $result['moi'] ?? null;
        $result['fdoi'] = $result['doi'] ? date('m/d/Y', strtotime($result['doi'])) : null;
        $result['noi'] = $result['noi'] ? preg_replace('/\s*POI:.*/', '', $result['noi']) : null;
        $result['noi'] = $result['noi'] ? preg_replace('/\s*TOI:.*/', '', $result['noi']) : null;
        $result['noi'] = $result['noi'] ? preg_replace('/\s*DOI:.*/', '', $result['noi']) : null;
        $result['poi'] = $result['poi'] ? preg_replace('/\s*DOI:.*/', '', $result['poi']) : null;
        $result['poi'] = $result['poi'] ? preg_replace('/\s*TOI:.*/', '', $result['poi']) : null;
        $result['ftoi'] = $result['toi'] ? date('h:i A', strtotime($result['toi'])) : null;
        // $result['ftoi'] = \DateTime::createFromFormat('h:i A', $result['toi'])->format('h:i A');

        return response()->json($result);
    }

    // public function generateReport(Request $r){

    // }



    // public function generateStatsToExcel(Request $r)
    // {
    //     // FacadesLog::info(['received data: ', $r->all]);
    //     $newdata = [];
    //     $data = $r->array;

    //     // foreach ($data as $d) {
    //     //     $r->replace($d);
    //     //     $newdata[] = $r;
    //     // }

    //     $csvFileName = 'Injury.csv';
    //     $zipFileName = 'Injury.zip';
    //     $filePath = storage_path('app/' . $csvFileName);
    //     $zipPath = storage_path('app/' . $zipFileName);

    //     $file = fopen($filePath, 'w');

    //     if (!empty($data)) {
    //         fputcsv($file, array_keys((array)$data[0]));
    //     }

    //     foreach ($data as $row) {
    //         fputcsv($file, (array)$row);
    //     }

    //     fclose($file);

    //     $zip = new ZipArchive();
    //     if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
    //         return response()->json(['error' => 'Cannot open zip file'], 500);
    //     }

    //     $zip->addFile($filePath, $csvFileName);
    //     $zip->close();

    //     if (file_exists($zipPath)) {
    //         return response()->download($zipPath)->deleteFileAfterSend(true);
    //     } else {
    //         return response()->json(['error' => 'Zip file not found'], 500);
    //     }
    // }
    public function generateStats(Request $r)
    {
        $result = DB::select('SELECT * FROM registry.dbo.GetInjuryCounts(?, ?, ?)', [$r->dateFrom, $r->dateTo, $r->isOneiss]);
        return response()->json($result);
    }
    public function generateStatsToExcel(Request $r)
    {
        $newdata = [];
        $data = $r->array;

        $csvFileName = 'Injury.csv';
        $zipFileName = 'Injury.zip';
        $filePath = storage_path('app/' . $csvFileName);
        $zipPath = storage_path('app/' . $zipFileName);

        $file = fopen($filePath, 'w');

        if (!empty($data)) {
            fputcsv($file, array_keys((array)$data[0]));
        }

        foreach ($data as $row) {
            fputcsv($file, (array)$row);
        }

        fclose($file);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return response()->json(['error' => 'Cannot open zip file'], 500);
        }

        // Set the password for the zip file
        $zip->setPassword('bghmcCensus');

        // Add the CSV file to the zip
        $zip->addFile($filePath, $csvFileName);
        // Encrypt the file with the password
        $zip->setEncryptionName($csvFileName, ZipArchive::EM_AES_256);

        $zip->close();

        if (file_exists($zipPath)) {
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Zip file not found'], 500);
        }
    }
    public function getArrayFromFrontEnd(Request $r)
    {
        // FacadesLog::info(['rolling: ', $r]);

        // FacadesLog::info(['rolling: ', $r->request->get('isAdmit')]); 
        $isAdmit = $r->request->get('isAdmit');
        $newdata = [];
        $data = $r->array;

        foreach ($data as $d) {
            $r->replace($d);
            $newdata[] = $this->formatEnccodeDataForCSV($r, $isAdmit);
        }


        $csvFileName = 'Injury.csv';
        $zipFileName = 'Injury.zip';
        // dd('here');
        // $zipFileName = 'file.zip';
        // $csvFileName = 'file' + date('mdYHis') + '.csv';
        // $zipFileName = 'Injury' + date('mdYHis') + '.zip';
        $filePath = storage_path('app/' . $csvFileName);
        $zipPath = storage_path('app/' . $zipFileName);

        $file = fopen($filePath, 'w');

        if (!empty($newdata)) {
            fputcsv($file, array_keys((array)$newdata[0]));
        }

        foreach ($newdata as $row) {
            fputcsv($file, (array)$row);
        }

        fclose($file);

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) !== TRUE) {
            return response()->json(['error' => 'Cannot open zip file'], 500);
        }

        $zip->addFile($filePath, $csvFileName);
        $zip->close();

        // Make sure the file exists before sending a download response
        if (file_exists($zipPath)) {
            return response()->download($zipPath)->deleteFileAfterSend(true);
        } else {
            return response()->json(['error' => 'Zip file not found'], 500);
        }
    }

    public function formatEnccodeDataForCSV(Request $r, $isAdmit)
    {

        // return $this->injuryPatient($r);
        // console.log(is);
        // dd($r->isAdmit);
        // FacadesLog::info(message: ['nganiii ',$r->request->get('isAdmit')]);
        // Get the isAdmit value

        // FacadesLog::info(['nganiii ', $isAdmit]);
        if ($isAdmit === TRUE) {
            $rowToExport = $this->admittedInjuryListData($r);
            // FacadesLog::info(message: ['nganiii ', $r->all]);
        } else {
            $rowToExport = $this->injuryPatientDev($r);
        }

        // FacadesLog::info(['data: ', $r->all]);
        // return $rowToExport;

        $csvObject = new stdClass();
        $csvObject->pat_last_name = $rowToExport->header->patlast;
        $csvObject->pat_first_name = $rowToExport->header->patfirst;
        $csvObject->pat_middle_name = $rowToExport->header->patmiddle ?? '';
        $csvObject->pat_current_address_region = $rowToExport->header->regcode ?? 'checkValue';
        $csvObject->pat_current_address_province = $rowToExport->header->provcode ?? 'checkValue';
        $csvObject->pat_current_address_city = $rowToExport->header->ctycode ?? 'checkValue';
        $csvObject->pat_sex = $rowToExport->header->patsex;
        $csvObject->pat_date_of_birth = $rowToExport->header->patbdate;
        $csvObject->age_years = $rowToExport->header->patage;
        $csvObject->age_months = '';
        $csvObject->age_days = '';
        $csvObject->plc_regcode = $rowToExport->details->generalData->plc_regcode;
        $csvObject->plc_provcode = $rowToExport->details->generalData->plc_provcode;
        $csvObject->plc_ctycode = $rowToExport->details->generalData->plc_ctycode;
        $csvObject->inj_date = Carbon::parse($rowToExport->header->injtme)->format('Y-m-d');
        // $csvObject->inj_time = $rowToExport->header->injtme;
        $csvObject->inj_time = Carbon::parse($rowToExport->header->injtme)->format('H:i');

        $csvObject->encounter_date = Carbon::parse($rowToExport->header->admdate)->format('Y-m-d');
        // $csvObject->encounter_time = $rowToExport->header->admdate;
        $csvObject->encounter_time = Carbon::parse($rowToExport->header->admdate)->format('H:i');

        // $csvObject->outcome_inpat = $rowToExport->header->outcome_inpat?? '';
        $csvObject->outcome_inpat = match ($rowToExport->header->outcome_inpat ?? '') {
            'DIEMI' => 30,
            'DIENA' => 30,
            'DIEPO' => 30,
            'DILNA' => 30,
            'DPONA' => 30,
            'IMPRO' => 10,
            'RECOV' => 10,
            'TRANS' => 20,
            'UNIMP' => 20,
            default => null,
        };

        $csvObject->disp_inpat = match ($rowToExport->header->disp_inpat ?? '') {
            'ABSC' => 'ABSCN',
            'DIED' => 'DIEDD',
            'DISCH' => 'DISCH',
            'DAMA' => 'DISCH',
            'TRANS' => 'REFER',
            'oth' => 'oth',
            default => null,
        };
        // $csvObject->disp_inpat = $rowToExport->header->disp_inpat ?? '';
        $complete_diagnosis = $rowToExport->header->complete_diagnosis ?? '';
        if (!empty($rowToExport->details->hospitalFacilityData->customizedFinalDiagnosis)) {
            $complete_diagnosis = $rowToExport->details->hospitalFacilityData->customizedFinalDiagnosis;
        }
        $csvObject->complete_diagnosis = $complete_diagnosis;


        $csvObject->inj_intent_code = $rowToExport->details->preAdmissionData->inj_intent_code;

        $csvObject->mult_inj = $rowToExport->details->natureOfInjury->mult_inj;
        $csvObject->noi_abrasion = $rowToExport->details->natureOfInjury->noi_abrasion;
        $csvObject->noi_abradtl = $rowToExport->details->natureOfInjury->noi_abradtl;
        $csvObject->noi_avulsion = $rowToExport->details->natureOfInjury->noi_avulsion;
        $csvObject->noi_avuldtl = $rowToExport->details->natureOfInjury->noi_avuldtl;
        $csvObject->noi_burndtl = $rowToExport->details->natureOfInjury->noi_burndtl;
        $csvObject->noi_concussion = $rowToExport->details->natureOfInjury->noi_concussion;
        $csvObject->noi_concussiondtl = $rowToExport->details->natureOfInjury->noi_concussiondtl;
        $csvObject->noi_contusion = $rowToExport->details->natureOfInjury->noi_contusion;
        $csvObject->noi_contudtl = $rowToExport->details->natureOfInjury->noi_contudtl;
        $csvObject->noi_frac_clo = $rowToExport->details->natureOfInjury->noi_frac_clo;
        $csvObject->noi_frcldtl = $rowToExport->details->natureOfInjury->noi_frcldtl;
        $csvObject->noi_frac_ope = $rowToExport->details->natureOfInjury->noi_frac_ope;
        $csvObject->noi_fropdtl = $rowToExport->details->natureOfInjury->noi_fropdtl;
        $csvObject->noi_owound = $rowToExport->details->natureOfInjury->noi_owound;
        $csvObject->noi_owoudtl = $rowToExport->details->natureOfInjury->noi_owoudtl;
        $csvObject->noi_amp = $rowToExport->details->natureOfInjury->noi_amp;
        $csvObject->noi_ampdtl = $rowToExport->details->natureOfInjury->noi_ampdtl;
        if ($rowToExport->details->natureOfInjury->noi_othersPhysical) {
            $csvObject->noi_others = 'Y';
            $csvObject->noi_otherinj = $rowToExport->details->natureOfInjury->noi_othersPhysical . ', ' . $rowToExport->details->natureOfInjury->noi_otherinj;
        } else {
            $csvObject->noi_others = $rowToExport->details->natureOfInjury->noi_others;
            $csvObject->noi_otherinj = $rowToExport->details->natureOfInjury->noi_otherinj;
        }

        $csvObject->ext_bite = $rowToExport->details->ExternalCauseOfInjury->ext_bite;
        $csvObject->ext_bite_sp = $rowToExport->details->ExternalCauseOfInjury->ext_bite_sp;
        $csvObject->ref_burn_code = $rowToExport->details->ExternalCauseOfInjury->ref_burn_code == '88' ? '06' : $rowToExport->details->ExternalCauseOfInjury->ref_burn_code;
        $csvObject->ext_burn_sp = $rowToExport->details->ExternalCauseOfInjury->ext_burn_sp;
        $csvObject->ext_chem = $rowToExport->details->ExternalCauseOfInjury->ext_chem;
        $csvObject->ext_chem_sp = $rowToExport->details->ExternalCauseOfInjury->ext_chem_sp;
        $csvObject->ext_sharp = $rowToExport->details->ExternalCauseOfInjury->ext_sharp;
        $csvObject->ext_sharp_sp = $rowToExport->details->ExternalCauseOfInjury->ext_sharp_sp;
        $csvObject->ref_drowning_cope = $rowToExport->details->ExternalCauseOfInjury->ref_drowning_cope;
        $csvObject->ext_fall = $rowToExport->details->ExternalCauseOfInjury->ext_fall;
        $csvObject->ext_falldtl = $rowToExport->details->ExternalCauseOfInjury->ext_falldtl;
        $csvObject->ext_gun = $rowToExport->details->ExternalCauseOfInjury->ext_gun;
        $csvObject->ext_gun_sp = $rowToExport->details->ExternalCauseOfInjury->ext_gun_sp;
        $csvObject->ext_hang = $rowToExport->details->ExternalCauseOfInjury->ext_hang;
        // $csvObject->ext_maul = $rowToExport->details->ExternalCauseOfInjury->ext_maul;
        $csvObject->ext_maul = $rowToExport->details->ExternalCauseOfInjury->ext_maul;

        // Check if ext_battery is 'Y' and set ext_maul to 'Y'
        // Check if ext_battery is defined and equals 'Y', then set ext_maul to 'Y'
        if (isset($rowToExport->details->ExternalCauseOfInjury->ext_battery) && $rowToExport->details->ExternalCauseOfInjury->ext_battery === 'Y') {
            $csvObject->ext_maul = 'Y';
        }

        // Check if ext_assault is defined and equals 'Y', then set ext_maul to 'Y'
        if (isset($rowToExport->details->ExternalCauseOfInjury->ext_assault) && $rowToExport->details->ExternalCauseOfInjury->ext_assault === 'Y') {
            $csvObject->ext_maul = 'Y';
        }
        $csvObject->ext_transport = $rowToExport->details->ExternalCauseOfInjury->ext_transport;
        // $csvObject->ext_other = $rowToExport->details->ExternalCauseOfInjury->ext_other;
        // $csvObject->ext_other_sp = $rowToExport->details->ExternalCauseOfInjury->ext_other_sp;
        if (isset($rowToExport->details->ExternalCauseOfInjury->ext_others_external) && $rowToExport->details->ExternalCauseOfInjury->ext_others_external) {
            $csvObject->ext_other = 'Y';
            $csvObject->ext_other_sp = $rowToExport->details->ExternalCauseOfInjury->ext_others_external . ', ' . ($rowToExport->details->ExternalCauseOfInjury->ext_other_sp ?? '');
        } else {
            $csvObject->ext_other = $rowToExport->details->ExternalCauseOfInjury->ext_other ?? null;
            $csvObject->ext_other_sp = $rowToExport->details->ExternalCauseOfInjury->ext_other_sp ?? null;
        }

        $csvObject->ref_veh_acctype_code = $rowToExport->details->forTransportVehicularAccident->ref_veh_acctype_code;
        $csvObject->vehicle_code = $rowToExport->details->forTransportVehicularAccident->vehicle_code;
        $csvObject->pat_veh_sp = $rowToExport->details->forTransportVehicularAccident->pat_veh_sp;
        $csvObject->etc_veh = $rowToExport->details->forTransportVehicularAccident->etc_veh;
        $csvObject->etc_veh_sp = $rowToExport->details->forTransportVehicularAccident->etc_veh_sp;
        $csvObject->position_code = $rowToExport->details->forTransportVehicularAccident->position_code;
        $csvObject->pos_pat_sp = $rowToExport->details->forTransportVehicularAccident->pos_pat_sp;
        $csvObject->safe_airbag = $rowToExport->details->forTransportVehicularAccident->safe_airbag;
        $csvObject->safe_cseat = $rowToExport->details->forTransportVehicularAccident->safe_cseat;
        $csvObject->safe_helmet = $rowToExport->details->forTransportVehicularAccident->safe_helmet;
        $csvObject->safe_sbelt = $rowToExport->details->forTransportVehicularAccident->safe_sbelt;
        $csvObject->safe_none = $rowToExport->details->forTransportVehicularAccident->safe_none;
        if ($rowToExport->details->forTransportVehicularAccident->safe_not_applicable === 'Y') {
            $csvObject->safe_none = 'N';
        }
        $csvObject->safe_unkn = $rowToExport->details->forTransportVehicularAccident->safe_unkn;
        $csvObject->safe_other = $rowToExport->details->forTransportVehicularAccident->safe_other;
        $csvObject->safe_other_sp = $rowToExport->details->forTransportVehicularAccident->safe_other_sp;
        $csvObject->place_occ_code = $rowToExport->details->preAdmissionData->place_occ_code ?? null;
        $csvObject->poc_wp_spec = $rowToExport->details->forTransportVehicularAccident->poc_wp_spec;
        $csvObject->poc_etc_spec = $rowToExport->details->preAdmissionData->poc_etc_spec;
        $csvObject->activity_code = $rowToExport->details->preAdmissionData->activity_code == '' ? '99' : $rowToExport->details->preAdmissionData->activity_code;
        $csvObject->risk_alcliq = $rowToExport->details->forTransportVehicularAccident->risk_alcliq == 1 ? 'Y' : $rowToExport->details->forTransportVehicularAccident->risk_alcliq;
        $csvObject->risk_sleep = $rowToExport->details->forTransportVehicularAccident->risk_sleep;
        $csvObject->risk_smoke = $rowToExport->details->forTransportVehicularAccident->risk_smoke;
        $csvObject->risk_mobpho = $rowToExport->details->forTransportVehicularAccident->risk_mobpho;
        $csvObject->risk_other = $rowToExport->details->forTransportVehicularAccident->risk_other;
        $csvObject->risk_etc_spec = $rowToExport->details->forTransportVehicularAccident->risk_etc_spec;
        $csvObject->trans_ref = $rowToExport->details->hospitalFacilityData->trans_ref;
        $csvObject->ref_hosp_code = $rowToExport->details->hospitalFacilityData->ref_hosp_code;
        $csvObject->ref_physician = $rowToExport->details->hospitalFacilityData->ref_physician;
        $csvObject->status_code = $rowToExport->details->hospitalFacilityData->status_code;
        $csvObject->stat_reachdtl = $rowToExport->details->hospitalFacilityData->stat_reachdtl;
        $diagnosis = $rowToExport->details->hospitalFacilityData->diagnosis ?? 'no diagnosis';
        if (($rowToExport->details->ExternalCauseOfInjury->vawc ?? null) === 'Y') {
            $diagnosis .= "\n-VAWC"; // Append 'VAWC' to the diagnosis
        }
        if (!empty($rowToExport->details->hospitalFacilityData->customizedDiagnosis)) {
            $diagnosis = $rowToExport->details->hospitalFacilityData->customizedDiagnosis;
        }
        //already in automated diagnosis
        // if (($rowToExport->details->ExternalCauseOfInjury->vawc ?? null) === 'Y') {
        //     $diagnosis .= ' VAWC'; // Append 'VAWC' to the diagnosis
        // }

        $csvObject->diagnosis = $diagnosis;

        $csvObject->icd_10_nature_er = $rowToExport->details->hospitalFacilityData->icd_10_nature_er;

        $csvObject->disposition_code = $rowToExport->header->dispcode;
        if ($rowToExport->header->dispcode === 'OWC') {
            $csvObject->disposition_code = 'ABSCN';
        }
        // $csvObject->disposition_code = $rowToExport->details->hospitalFacilityData->disposition_code;
        // $csvObject->disposition_code = $rowToExport->details->hospitalFacilityData->disposition_code === 'oth' ? 'unk' : $rowToExport->details->hospitalFacilityData->disposition_code;
        // $csvObject->outcome_code = $rowToExport->header->outcome_code ?? '10';
        //         // $csvOb
        // $csvObject->outcome_code = $rowToExport->header->outcome_inpat ?? $rowToExport->details->hospitalFacilityData->condition_code ?? '10';
        if (
            isset($rowToExport->header->outcome_inpat)
        ) {

            $csvObject->outcome_code = match ($rowToExport->header->outcome_inpat ?? '') {
                'DIEMI' => 30,
                'DIENA' => 30,
                'DIEPO' => 30,
                'DILNA' => 30,
                'DPONA' => 30,
                'IMPRO' => 10,
                'RECOV' => 10,
                'TRANS' => 20,
                'UNIMP' => 20,
                default => null,
            };
        } else {
            $csvObject->outcome_code = $rowToExport->header->outcome_inpat ?? $rowToExport->details->hospitalFacilityData->condition_code ?? '10';
        }
        // $csvObject->disp_inpat = $rowToExport->details->inPatient->disp_inpat;
        // $csvObject->outcome_inpat = $rowToExport->details->inPatient->outcome_inpat;
        $csvObject->ext_drown_sp = $rowToExport->details->ExternalCauseOfInjury->ext_drown_sp;
        $csvObject->first_aid_code = $rowToExport->details->preAdmissionData->first_aid_code;
        $csvObject->firstaid_others = $rowToExport->details->preAdmissionData->firstaid_others;
        if (($rowToExport->details->ExternalCauseOfInjury->washingDone ?? null) === 'YES') {
            $csvObject->firstaid_others = 'Washing of Wound Done. ' . $csvObject->firstaid_others;
        }
        $csvObject->firstaid_others2 = $rowToExport->details->preAdmissionData->firstaid_others2;
        $csvObject->ref_expnature_code = $rowToExport->details->ExternalCauseOfInjury->ref_expnature_code;
        $csvObject->safe_drown = '';
        $csvObject->firecracker_code = $rowToExport->details->ExternalCauseOfInjury->firecracker_code;



        // $csvObject->ext_firecracker_sp = $rowToExport->details->ExternalCauseOfInjury->ext_firecracker_sp;
        $csvObject->mode_transport_code = $rowToExport->details->hospitalFacilityData->mode_transport_code;
        $csvObject->disp_er_sp = $rowToExport->details->hospitalFacilityData->disp_er_sp;
        $csvObject->disp_inpat_sp = '';
        $csvObject->comments = $rowToExport->details->inPatient->comments;
        $csvObject->pno = '';
        $csvObject->pre_date = '';
        $csvObject->ptype_code = $rowToExport->details->hospitalFacilityData->ptype_code;
        $csvObject->status = $rowToExport->details->status;
        $csvObject->hosp_reg_no = '';
        $csvObject->hosp_cas_no = '';
        $csvObject->temp_regcode = '';
        $csvObject->temp_provcode = '';
        $csvObject->temp_citycode = '';
        // $csvObject->act_etc_spec = $rowToExport->details->preAdmissionData->act_etc_spec;
        $csvObject->act_etc_spec = $rowToExport->details->preAdmissionData->activity_code != '88' ? '' : $rowToExport->details->preAdmissionData->act_etc_spec;



        // ********************************************FACTOR SAFETY******************************************* 

        $csvObject->ref_hosp_code_sp = $rowToExport->details->hospitalFacilityData->ref_hosp_code_sp;
        $csvObject->disp_inpat_oth = $rowToExport->details->inPatient->disp_inpat_oth;
        $csvObject->disp_inpat_sp2 = $rowToExport->details->inPatient->disp_inpat_sp2;
        $csvObject->noi_burn_r = $rowToExport->details->natureOfInjury->noi_burn_r;
        $csvObject->ext_burn_r = $rowToExport->details->ExternalCauseOfInjury->ext_burn_r;
        $csvObject->ext_drown_r = $rowToExport->details->ExternalCauseOfInjury->ext_drown_r;
        $csvObject->ext_firecracker_r = $rowToExport->details->ExternalCauseOfInjury->ext_firecracker_r;
        $csvObject->ext_firecracker_sp = $rowToExport->details->ExternalCauseOfInjury->ext_firecracker_sp;

        // ********************** VAPOR HTD****************************
        // $csvObject->ext_vape = $rowToExport->details->ExternalCauseOfInjury->ext_vape??'';
        //      //

        $csvObject->ext_sexual = $rowToExport->details->ExternalCauseOfInjury->ext_sexual;
        $csvObject->vehicle_type_id = $rowToExport->details->forTransportVehicularAccident->vehicle_type_id;
        $csvObject->ext_expo_nature_r = $rowToExport->details->ExternalCauseOfInjury->ext_expo_nature_r;
        $csvObject->ext_expo_nature_sp = $rowToExport->details->ExternalCauseOfInjury->ext_expo_nature_sp;
        $csvObject->trans_ref2 = $rowToExport->details->hospitalFacilityData->trans_ref2;
        $csvObject->icd_10_external_er = $rowToExport->details->hospitalFacilityData->icd_10_external_er;
        // $csvObject->complete_diagnosis = $rowToExport->details->inPatient->complete_diagnosis;
        $csvObject->icd10_nature_inpatient = $rowToExport->details->inPatient->icd10_nature_inpatient;
        $csvObject->icd_10_ext_inpatient = $rowToExport->details->inPatient->icd_10_ext_inpatient;
        $csvObject->disp_er_sp_oth = $rowToExport->details->hospitalFacilityData->disp_er_sp_oth;
        $csvObject->time_report = $rowToExport->details->generalData->time_report;
        $csvObject->date_report = $rowToExport->details->generalData->date_report;


        $csvObject->risk_none = $rowToExport->details->forTransportVehicularAccident->risk_none;
        if ($rowToExport->details->forTransportVehicularAccident->risk_not_applicable === 'Y') {
            $csvObject->risk_none = 'N';
        }
        $csvObject->rstatuscode = '';
        $csvObject->pat_phil_health_no = $rowToExport->details->generalData->pat_phil_health_no;
        $csvObject->pat_facility_no = '';

        // Log::debug('csvObject', [$csvObject]);
        $burnDetailsDoctor = [
            ['ref_burn_code' => '99', 'ref_burn_desc' => 'Flame Burn'],
            ['ref_burn_code' => '98', 'ref_burn_desc' => 'Scalp burn'],
            ['ref_burn_code' => '97', 'ref_burn_desc' => 'Electrical Burn'],
            ['ref_burn_code' => '96', 'ref_burn_desc' => 'Flash Burn'],
            ['ref_burn_code' => '95', 'ref_burn_desc' => 'Chemical Burn'],
            ['ref_burn_code' => '94', 'ref_burn_desc' => 'Blast Injury'],
            ['ref_burn_code' => '93', 'ref_burn_desc' => 'Contact Burn'],
            ['ref_burn_code' => '92', 'ref_burn_desc' => 'Others, Specify'],
        ];


        if (
            isset($rowToExport->details->ExternalCauseOfInjury->ext_burn_r_doctor) &&
            $rowToExport->details->ExternalCauseOfInjury->ext_burn_r_doctor === 'Y'
        ) {
            FacadesLog::info($rowToExport->details->ExternalCauseOfInjury->ref_burn_code_doctor);
            $csvObject->ext_burn_r = 'Y';
            $doctorBurnCode = $rowToExport->details->ExternalCauseOfInjury->ref_burn_code_doctor;

            if (in_array($doctorBurnCode, ['98', '96', '93'])) {
                //  Scalp Burn, Flash Burn, Contact Burn fall under Heat (01)
                $csvObject->ref_burn_code = '01'; // Heat
            } elseif ($doctorBurnCode === '99') {
                // Flame Burn
                $csvObject->ref_burn_code = '02'; // Fire
            } elseif ($doctorBurnCode === '97') {
                // Electrical Burn
                FacadesLog::info('Electrical');
                $csvObject->ref_burn_code = '03'; // Electricity
                FacadesLog::info($csvObject->ext_burn_r);
                FacadesLog::info($csvObject->ref_burn_code);
            } elseif ($doctorBurnCode === '95') {
                // Chemical Burn
                $csvObject->ref_burn_code = '06'; // Others, specify

                $burnDetail = collect($burnDetailsDoctor)->firstWhere('ref_burn_code', $doctorBurnCode);
                $csvObject->ext_burn_sp = $burnDetail ? $burnDetail['ref_burn_desc'] : 'Unknown Burn Type';
            } elseif ($doctorBurnCode === '94') {
                // Blast Injury
                $csvObject->ref_burn_code = '06'; // Others, specify

                $burnDetail = collect($burnDetailsDoctor)->firstWhere('ref_burn_code', $doctorBurnCode);
                $csvObject->ext_burn_sp = $burnDetail ? $burnDetail['ref_burn_desc'] : 'Unknown Burn Type';
            } elseif ($doctorBurnCode === '92') {
                // Others, Specify
                $csvObject->ref_burn_code = '06'; // Others, specify
                $csvObject->ext_burn_sp = $rowToExport->details->ExternalCauseOfInjury->ext_burn_sp_doctor;
            } else {
                $csvObject->ref_burn_code = 'Unknown Burn Type';
            }
        }
        // FacadesLog::info(['csv: ', $csvObject]);
        return $csvObject;
    }
}
