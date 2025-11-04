<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\InjuryServicesContoller;
use App\Http\Controllers\InjuryServicesController;
use App\Http\Controllers\InjuryTasksController;
use App\Http\Controllers\TasksController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Public routes
Route::post('/login', [AuthController::class, 'login']);
// Route::post('/loginbyid', [AuthController::class, 'loginByID']);
Route::get('/fromEMR', [AuthController::class, 'loginById']);
Route::get('/fromOPD', [AuthController::class, 'loginOPD']);
Route::get('/opdPatientData', [App\Http\Controllers\InjuryServicesController::class, 'opdPatientData']);
// Route::post('/register', [AuthController::class, 'register']);

Route::get('/test-cookie', function () {
    return response('Test Cookie')
        ->cookie('test_cookie', 'test_value', 60, '/', '.bghmc.com', true, true, false, 'None');
});

Route::get('/csrf', function () {
    return response()->json(['csrfToken' => csrf_token()]);
    // return csrf_token();
});
// Protected routes


Route::middleware(['cors'])->group(function () {
    Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show'])->name('sanctum.csrf-cookie');
});
// Route::get('/provinceByRegion', [App\Http\Controllers\GeneralServicesContoller::class, 'provinceByRegion']);
//  Route::get('/cityByProvince', [App\Http\Controllers\GeneralServicesContoller::class, 'cityByProvince']);
//  Route::get('/regions', [App\Http\Controllers\GeneralServicesContoller::class, 'regions']);
// Route::get('/bgyByCity', [App\Http\Controllers\GeneralServicesContoller::class, 'bgyByCity']);

// Route::get('/cityByProvince', [App\Http\Controllers\GeneralServicesContoller::class, 'cityByProvince']);
Route::group(
    ['middleware' => ['auth:sanctum']],
    function () {
        //AUTH ROUTES
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/getUserInfo', [AuthController::class, 'getUserInfo']);
        // Route::resource('/injurytasks', InjuryTasksController::class);

        //HOSPAPI ROUTES
        // Route::get('/user/{username}', [AuthController::class, 'getUserInfo']);
        // Route::post('/getUserByToken', [AuthController::class, 'getUserByToken']);


        //GENERAL ROUTES
        Route::post('/searchPatient', [App\Http\Controllers\GeneralServicesContoller::class, 'searchPatient']);



        //INJURY ROUTES
        Route::get('/locations', [App\Http\Controllers\GeneralServicesContoller::class, 'locations']);
        Route::get('/newlocations', [App\Http\Controllers\GeneralServicesContoller::class, 'newlocations']);
        Route::get('/newRegion', [App\Http\Controllers\GeneralServicesContoller::class, 'newRegion']);
        Route::get('/newProvince', [App\Http\Controllers\GeneralServicesContoller::class, 'newProvince']);
        Route::get('/newCity', [App\Http\Controllers\GeneralServicesContoller::class, 'newCity']);
        Route::get('/newBarangay', [App\Http\Controllers\GeneralServicesContoller::class, 'newBarangay']);

        Route::prefix('locations')->group(function () {

            Route::get('/provinceByRegion', [App\Http\Controllers\GeneralServicesContoller::class, 'provinceByRegion']);
            Route::get('/cityByProvince', [App\Http\Controllers\GeneralServicesContoller::class, 'cityByProvince']);
            Route::get('/provinceByRegionDesc', [App\Http\Controllers\GeneralServicesContoller::class, 'provinceByRegionDesc']);
            Route::get('/citiesByProvinceDesc', [App\Http\Controllers\GeneralServicesContoller::class, 'citiesByProvinceDesc']);
            Route::get('/bgyByCity', [App\Http\Controllers\GeneralServicesContoller::class, 'bgyByCity']);
            Route::get('/regions', [App\Http\Controllers\GeneralServicesContoller::class, 'regions']);
            Route::get('/cities', [App\Http\Controllers\GeneralServicesContoller::class, 'cities']);
        });




        Route::get('/checkPatientTSSRecord', [App\Http\Controllers\InjuryServicesController::class, 'checkPatientTSSRecord']);
        Route::get('/getEmployeeName', [App\Http\Controllers\InjuryServicesController::class, 'getEmployeeName']);
        Route::get('/getPatientReferral', [App\Http\Controllers\InjuryServicesController::class, 'getPatientReferral']);

        // Route for fetching ABTC form data
        Route::get('/getABTCPhilhealthForm', [InjuryServicesController::class, 'getABTCPhilhealthForm']);

        // Route for generating the PDF
        Route::post('/generate-pdf', [InjuryServicesController::class, 'generateABTCPdf']);
        Route::post('/reportList', [App\Http\Controllers\InjuryServicesController::class, 'reportList']);
        Route::post('/injuryList', [App\Http\Controllers\InjuryServicesController::class, 'injuryList']);
        Route::post('/injuryListDev', [App\Http\Controllers\InjuryServicesController::class, 'injuryListDev']);
        Route::get('/injuryList2', [App\Http\Controllers\InjuryServicesController::class, 'injuryList2']);
        Route::post('/injuryList3', [App\Http\Controllers\InjuryServicesController::class, 'injuryList3']);
        Route::post('/admittedInjuryList', [App\Http\Controllers\InjuryServicesController::class, 'admittedInjuryList']);
        Route::get('/injuryList/{enccode}', [App\Http\Controllers\InjuryServicesController::class, 'injuryPatient']);
        Route::get('/admittedInjuryListData/{enccode}', [App\Http\Controllers\InjuryServicesController::class, 'admittedInjuryListData']);
        Route::get('/injuryListDev/{enccode}', [App\Http\Controllers\InjuryServicesController::class, 'injuryPatientDev']);
        Route::get('/injuryListNoData/{enccode}', [App\Http\Controllers\InjuryServicesController::class, 'injuryPatientNoData']);
        Route::get('/injuryList2/{enccode}', [App\Http\Controllers\InjuryServicesController::class, 'injuryPatient2']);
        // Route::post('/user/{username}', [App\Http\Controllers\InjuryServicesController::class, 'getUserInfo']);
        Route::put('/exportToExcelSaveData', [App\Http\Controllers\InjuryServicesController::class, 'exportToExcelSaveData']);

        Route::post('/saveData', [App\Http\Controllers\InjuryServicesController::class, 'saveData']);
        Route::post('/saveOPDData', [App\Http\Controllers\InjuryServicesController::class, 'saveOPDData']);
        Route::get('/getPatientABTCLog', [App\Http\Controllers\InjuryServicesController::class, 'getPatientABTCLog']);
        Route::get('/getLatestEntryOfDoctors', [App\Http\Controllers\InjuryServicesController::class, 'getLatestEntryOfDoctors']);
        // Route::get('/getLatestDiagnosis', [App\Http\Controllers\InjuryServicesController::class, 'getLatestDiagnosis']);
        Route::get('/getEntryOfDoctors', [App\Http\Controllers\InjuryServicesController::class, 'getEntryOfDoctors']);
        Route::get('/getFormDetail', [App\Http\Controllers\InjuryServicesController::class, 'getFormDetail']);
        Route::get('/getFormDetail', [App\Http\Controllers\InjuryServicesController::class, 'getFormDetail']);
        Route::put('/updateStatusForArchived', [App\Http\Controllers\InjuryServicesController::class, 'updateStatusForArchived']);
        Route::put('/newCase', [App\Http\Controllers\InjuryServicesController::class, 'newCase']);
        Route::put('/insertObjSubj', [App\Http\Controllers\InjuryServicesController::class, 'insertObjSubj']);
        Route::put('/updateMedilogs', [App\Http\Controllers\InjuryServicesController::class, 'updateMedilogs']);
        Route::put('/insertBillingDiagnosis', [App\Http\Controllers\InjuryServicesController::class, 'insertBillingDiagnosis']);
        Route::put('/insertDiagnosis', [App\Http\Controllers\InjuryServicesController::class, 'insertDiagnosis']);
        Route::put('/updatePrimediag', [App\Http\Controllers\InjuryServicesController::class, 'updatePrimediag']);
        Route::post('/removeFromRegistry', [App\Http\Controllers\InjuryServicesController::class, 'removeFromRegistry']);
        Route::get('/generateStats', [App\Http\Controllers\InjuryServicesController::class, 'generateStats']);
        Route::get('/getListOfDiagnosis', [App\Http\Controllers\InjuryServicesController::class, 'getListOfDiagnosis']);
        Route::get('/getListOfFinalDiagnosis', [App\Http\Controllers\InjuryServicesController::class, 'getListOfFinalDiagnosis']);

        // Route::post('/generateStatsToExcel', [App\Http\Controllers\InjuryServicesController::class, 'generateStatsToExcel']);



        Route::post('/getSubjective', [App\Http\Controllers\InjuryServicesController::class, 'getSubjective']);
        Route::post('/getSubjectiveNoiToiPoiDoi', [App\Http\Controllers\InjuryServicesController::class, 'getSubjectiveNoiToiPoiDoi']);
        Route::post('/getKeyTerms', [App\Http\Controllers\InjuryServicesController::class, 'getKeyTerms']);

        Route::post('/extractNOIPOIDOITOIMOI', [App\Http\Controllers\InjuryServicesController::class, 'extractNOIPOIDOITOIMOI']);
        // Route::post('/getArrayFromFrontEnd', [App\Http\Controllers\InjuryServicesController::class, 'getArrayFromFrontEnd']);
        // 


        // Route::prefix('cancer')->group(
        //     function () {
        //         Route::get('/patientList', [App\Http\Controllers\CancerServicesController::class, 'cancerRegistryPatientList']);
        //     }
        // );

    }
);

//List of Unfinished Doctor's TSS Form
Route::get('/getUnfinishedTSSForms', [App\Http\Controllers\InjuryServicesController::class, 'getUnfinishedTSSForms']);

// moved export to unguarded
Route::post('/getArrayFromFrontEnd', [App\Http\Controllers\InjuryServicesController::class, 'getArrayFromFrontEnd']);
Route::post('/generateStatsToExcel', [App\Http\Controllers\InjuryServicesController::class, 'generateStatsToExcel']);

/* ML ROUTES */
// Route::post('/getKeyTerms', [App\Http\Controllers\MLServicesController::class, 'getKeyTerms']);
Route::post('/getNoiToiPoiDoi', [App\Http\Controllers\MLServicesController::class, 'getNoiToiPoiDoi']);

/*cancer routes*/
Route::group(
    ['middleware' => ['auth:sanctum']],
    function () {
        Route::prefix('cancer')->group(
            function () {
                Route::get('/patientList', [App\Http\Controllers\CancerServicesController::class, 'cancerRegistryPatientList']);
                Route::post('/cancerPatient', [App\Http\Controllers\CancerServicesController::class, 'cancerPatientHeader']);
                Route::post('/cancerDataPerSection', [App\Http\Controllers\CancerServicesController::class, 'cancerDataPerSection']);
                Route::post('/saveCancerData', [App\Http\Controllers\CancerServicesController::class, 'saveCancerData']);
                Route::post('/getMostRecentDX', [App\Http\Controllers\CancerServicesController::class, 'getMostRecentDX']);
                Route::get('/cancerMasterList', [App\Http\Controllers\CancerServicesController::class, 'cancerMasterList']);
                Route::post('/upload', [App\Http\Controllers\CancerServicesController::class, 'uploadFile']);
                Route::post('/getupload', [App\Http\Controllers\CancerServicesController::class, 'getUploadedFiles']);
                Route::post('/getEncounters', [App\Http\Controllers\CancerServicesController::class, 'getAllEncounters']);
            }
        );
    }
);



Route::prefix('billing')->group(
    function () {

        Route::get('/billStatusList', [App\Http\Controllers\BillingServicesController::class, 'billStatusList']);
    }
);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
