<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
// use App\Http\Controllers\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class CancerServicesController extends Controller
{
    //
    public function cancerRegistryPatientList(Request $r = null)
    {

        // $result = DB::select('exec registry.dbo.spCancerMasterList');
        $result = DB::table('registry.cancer.vwcCancerMasterList')
            ->limit(1000)
            ->orderBy('patlast', 'asc')
            ->orderBy('patfirst', 'asc')
            ->get();

        return response()->json($result);
    }



    public function cancerPatientHeader(Request $r){
        // dd($r);
        $result = DB::select("exec registry.cancer.cancerRegisterHeader ?", [$r->hpercode]);

        return response()->json($result);
    
    }

    public function cancerPatientFamilyHistory(request $r){
        $result = DB::table('registry.cancer.familyHistoryOfCancer as fhc')
                ->join('registry.cancer.familyHistoryMembers as fhm', 'fhc.id', '=', 'fhm.familyHistoryMembers_id')
                ->get();
        
        return response()->json($result);

    }

    public function cancerDataPerSection(request $r){
        
        try {
        $result = DB::select('exec registry.cancer.spCancerGetDataPerSection ?,?', [$r->hpercode, $r->section]);

        $result = json_decode($result[0]->data);

        return response()->json($result);
        }
        catch (QueryException $e) {
            // Log the error
            Log::error('Database error: ' . $e->getMessage());
    
            // Return a JSON response indicating an error
            return response()->json([
                'success' => false,
                'message' => 'A database error occurred.',
                'server error' => $e->getMessage()
            ], 500);
        }
    }

    public function saveCancerData(request $r){

        try{

            // dump($r->data);
            $result = DB::select('exec registry.cancer.saveCancerDataJSON ?', [$r->data]);
            return response()->json([
                'success' => true
                
            ]);
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

        
    }

    public function getMostRecentDX(request $r){

        try {

            $result = DB::table('hospital.dbo.hencdiag as dx')
            // ->join('hospital.dbo.hopdlog as opd', 'opd.enccode', '=', 'dx.enccode')
            ->join('hospital.dbo.henctr as opd', 'opd.enccode', '=', 'dx.enccode')
            ->select('diagtext')
            ->where('opd.hpercode', '=', $r->hpercode)
            ->where('tdcode', '=', 'FINDX')
            ->where('primediag', '=', 'Y')
            ->orderBy('dx.encdate', 'desc')
            ->first();
      
            
 
            echo $result;
            Log::debug('dx', $result);
 
            if (!$result) {
                $result = DB::table('hospital.dbo.hencdiag_adm as dx')
                    ->join('hospital.dbo.henctr as opd', 'opd.enccode', '=', 'dx.enccode')
                    ->select('diagtext')
                    // ->where('opd.hpercode', '=', $r->hpercode)
                    // ->where('tdcode', '=', 'FINDX')
                    ->where('primediag', '=', 'Y')
                    ->orderBy('dx.tstamp', 'desc')
                    ->first();
       

            return response()->json($result);
            }
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

    }


    public function cancerMasterList(Request $r = null)
    {

        // $result = DB::select('exec registry.dbo.spCancerMasterList');
        $result = DB::table('registry.dbo.vwCancerMasterList2024')
            ->limit(1000)
            ->orderBy('patlast', 'asc')
            ->orderBy('patfirst', 'asc')
            ->get();

        return response()->json($result);
    }

    
    public function uploadFile(Request $request)
    {

        $request->validate([
            'file.*' => 'required|file',
        ]);


        $decode = json_decode($request->data);


        $currentDate = now();
        [$year, $month, $day] = explode('-', $currentDate->toDateString());

        $lastTwoDigitsOfYear = $currentDate->format('y');



        $baseDirectory =  'patient' . '/' . $lastTwoDigitsOfYear . '/' . $month . '/' . $day . '/' . $decode->hpercode;
        // hpercode=$decode->hpercode
        // enccode=??
        // file_name=$file->getClientOriginalName()
        // file_src=$baseDirectory
        // entry_by=??
        // file_types_code=??


        // Create the base directory if it doesn't exist
        if (!File::exists($baseDirectory)) {
            File::makeDirectory($baseDirectory, 0777, true, true);
        }

        $files = $request->file('file');
        // dd($files);

        // Loop through each uploaded file
        foreach ($files as $file) {
            // Validate and process each file
            $filename = $file->getClientOriginalName();

            try {
                // Move the uploaded file to the destination directory
                // $path = $file->move($baseDirectory, $filename);
                Storage::disk('fileuploader')->putFileAs($baseDirectory,$file,$filename);
                // save $path to the database
                // save $filename to the database
                // get other variables from request and save it to the database


            } catch (\Exception $e) {
                // Handle the exception (e.g., log the error or return an error response)
                return response()->json(['error' => 'File upload failed.']);
            }
        }

        // Optionally, return a success response
        return response()->json(['success' => 'Files uploaded successfully.']);
    }


    public function getUploadedFiles(Request $r){
        //manggagaling database convert to pdf ulit if base 64

        //query from database
        $result = DB::table('hospital.dbo.file_src')
        ->where('hpercode', '=', $r->hpercode)
        ->whereNotIn('file_types_code', ['SIGN'])
        ->orderBy('created_at', 'desc')
        ->get();

        return response()->json($result);
        //loop it

    }

    public function getAllEncounters(request $r){
        //this is for all
        // $result = DB::select("select * from hospital.les.allencounters('000000001398070')");

        //this is for the very recent
        $result = DB::select("select * from hospital.les.allencounters(?)", [$r->hpercode])[0];
        return response()->json($result);
    }

}

