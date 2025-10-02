<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;
use Illuminate\Support\Facades\Cookie;

use Illuminate\Support\Facades\Log as FacadesLog;

class AuthController extends Controller
{
    use HttpResponses;

    // use Illuminate\Http\Request;

    public function loginByID(Request $r)
    {
        // return $r->empID;
        // dump($r->empID);
        $creds = $this->setCredsByEmpID($r->empID);
        // return $creds;
        // return $creds->user_name;

        $loginRequest = new LoginUserRequest([
            'username' => $creds->user_name,
            'password' => $creds->user_pass
        ]); 
        // $loginRequest->enccode = $r->enccode;
        $loginRequest['enccode'] = $r->enccode;
        // return $loginRequest;
        $token = $this->login($loginRequest);
        // return $token;
        // return $token->data;
        // return $token;
        $array = $token->getData(true);

        // return $array;
        /*
        // $response = redirect("https://192.168.7.66/#/injury/{$r->enccode}");
        // $response = redirect("https://192.168.7.66/#/injury/{$r->enccode}?token={$array['data']['token']['plainTextToken']}");
        $response = redirect("http://localhost:5173/#/injury?enccode={$r->enccode}&access_token={$array['data   ']['token']['plainTextToken']}");
        */
        // $response = redirect("http://192.168.7.66/#/injury/PatientFromEMR?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}&empID={$r->empID}");
        // $response = redirect("http://192.168.6.58:62043/#/injury/PatientFromEMR?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}&empID={$r->empID}");
        $response = redirect("http://localhost:5173/#/injury/PatientFromEMR?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}&empID={$r->empID}");

        // dd($response);
        // $response = redirect("https://192.168.7.66/#/injury/PatientFromEMR?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}&empID={$r->empID}");
        // $response = redirect("https://192.168.7.66/#/injury?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}");

        // $response = redirect("http://localhost:5173/#/injury/{$r->enccode}");
        // $response->withCookie(Cookie::make('access_token', $array['data']['token']['plainTextToken'], 60, null, null, true, true));

        // $response->withCookie(Cookie::make('access_token', $array['data']['token']['plainTextToken'], 60, '/', '192.168.7.66', true, true, false, 'none'));

        // $response->withCookie(Cookie::make('access_token', $array['data']['token']['plainTextToken'], 60, '/', '192.168.7.74', true, true, false, 'none'));

        // $response->withCookie(Cookie::make('authToken', $array['data']['token']['plainTextToken'], 60, '/', '192.168.7.66', true, true, false, 'None'));
        // $response->withCookie(Cookie::make('empID', $r->empID, 60, '/', '192.168.7.66', true, true, false, 'None'));
        // $response->withCookie(Cookie::make('enccode', $r->enccode, 60, '/', '192.168.7.66', true, true, false, 'None'));
        // FacadesLog::info('login complete', ['logRequest' => $r, 'logResponse' => $response]);

        // $response->setTargetUrl("https://192.168.7.66/#/injury/PatientFromEMR");
        return $response;

        // $token->enccode = $r->enccode;
        // $plainTextToken = $array['data']['token']['plainTextToken'];
        // $redirectUrl = 'https://192.168.7.66/#/injury/' . $r->enccode . '?token=' . $array['data']['token']['plainTextToken'];
        // return $redirectUrl;
        // return redirect()->to($redirectUrl);
        // return [
        //     'token' => $token,
        //     'enccode' => $r->enccode
        // ];
    }
    public function loginOPD(Request $r)
    {
        $creds = $this->setCredsByEmpID($r->empID);
        $loginRequest = new LoginUserRequest([
            'username' => $creds->user_name,
            'password' => $creds->user_pass
        ]);
        $loginRequest['enccode'] = $r->enccode;
        $token = $this->login($loginRequest);
        $array = $token->getData(true);
        $response = redirect("http://localhost:5173/#/injury/PatientFromOPD?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}&empID={$r->empID}");
        // $response = redirect("http://192.168.6.58:81/#/injury/PatientFromOPD?enccode={$r->enccode}&access_token={$array['data']['token']['plainTextToken']}&empID={$r->empID}");

        return $response;
    }

    // public function setCredsByEmpID(request $r){
    public function setCredsByEmpID(string $empID)
    {
        // return $empID;
        // $result = DB::table(('hospital.dbo.user_acc as ua'))
        // ->select('user_name', 'webapp.dbo.ufn_crypto(webapp.dbo.ufn_crypto(user_pass ,1) ,0) as user_pass')
        // ->where('employeeid', '=', $r->empID)
        // ->get();

        // return $result;


        $result = DB::table('hospital.dbo.user_acc as ua')
            // ->select('ua.user_name', DB::raw("webapp.dbo.ufn_crypto(webapp.dbo.ufn_crypto('ua.user_pass' ,1) ,0) as user_pass"))
            // ->select('ua.user_name', DB::raw("webapp.dbo.ufn_crypto('0' ,1) as user_pass"))
            ->select('ua.user_name', DB::raw("webapp.dbo.ufn_crypto(ua.user_pass ,0) as user_pass"))
            ->where('ua.employeeid', '=', $empID)
            // ->where('ua.employeeid', '=', $r->empID)

            ->get()[0];
        $result->empID = $empID;
        // dd($result);
        // $result->empID = $r->empID;
        return $result;
    }

    public function login(LoginUserRequest $request)
    // public function login(Request $request)
    {

        // if ($request->empID) {
        //     return $request;
        //     $creds = $this->setCredsByEmpID($request->empID);
        //     // return $creds;
        //     // $request->username = 'test';
        //     // $request->password = 'testps';
        //     $request->username = $creds->user_name;
        //     $request->password = $creds->user_pass;
        //     // return $request;

        // }

        // $request->validated($request->all());




        $dbresult = DB::select('exec hospAPI.dbo.spUserExists2 ?, ?', [$request->username, $request->password])[0]->exist;
        /*
        $dbresult: 
        -1 -> user does not exist in user_acc
        0 -> credentials in user_acc doe not match
        1 -> valid user_acc credentials, proceed to hospAPI.dbo.users
        */
        // dd($dbresult);
        // clock($dbresult);
        if ($dbresult == -1) {

            // return $this->error('', 'hospital.dbo.user_acc: User "' . $request->username . '" does not exist', 401);
            return $this->error($request->username, "hospital.dbo.user_acc: User '$request->username' does not exist", 500);
        }
        if ($dbresult == 0) {

            //return DB::select('exec hospAPI.dbo.spUserExists2 ?, ?', [$request->username, $request->password]);
            // return $this->error('', 'hospital.dbo.user_acc: Credentials do not match', 401);
            if ($request->empID) return $this->error($this->setCredsByEmpID($request), 'hospital.dbo.user_acc: Credentials do not match', 401);

            return $this->error($request->all(), 'hospital.dbo.user_acc: Credentials do not match', 401);
            // return 'hospital.dbo.user_acc: Invalid username/password';
        }
        $user = new User();

        // dd($user, $dbresult);

        /* if user does not exist in hospAPI.dbo.users, register the user */
        // $test = User::where('username', 'lester')
        // // ->where('password', bcrypt($request->password))
        // ->first();
        // clock()->info('les', $test->username);
        // clock()->info(User::where('username', $request->username)->first());
        if (!User::where('username', $request->username)
            // ->where('password', bcrypt($request->password))
            ->first()) {
            clock('if user does not exist in hospAPI.dbo.users, register the user');
            // clock()
            $user = User::create([
                'name' => $request->username,
                'username' => $request->username,
                'password' => bcrypt($request->password)
            ]);
        }



        /* if user is not authenticated in hospAPI.dbo.users, throw error*/
        // clock($request->username, $request->password);


        // if (!Auth::attempt(['username' => $request->username, 'password' => $request->password])) { //while fixing decryption **commented by NAD

        if ((!Auth::attempt(['username' => $request->username, 'password' => $request->password])) && (!$dbresult == 1)) { // NAD: Accepts auth if valid user_acc credentials, proceed to hospAPI.dbo.users
            // clock()->info('401');
            // dd($request->all());
            return $this->error('', 'hospAPI.dbo.users: Credentials do not match', 401);
        }


        /* get user that matches username in hospAPI.dbo.users */
        $user = User::where('username', $request->username)->first();
        // dd($user);
        $token = $user->createToken('API token of ' . $user->username);
        // $token->ttl = 120;
        // ->plainTextToken;
        /* get user that matches username in hospAPI.dbo.users */
        // Cookie::queue(
        //     'access_token',
        //     $token,
        //     60,
        //     null,
        //     null,
        //     true,
        //     true
        // );

        // return and GENERATE TOKEN
        return $this->success([
            'user' => $user,
            'token' => $token

            // ])->withCookie(Cookie::make('access_token', $token->plainTextToken, 60, null, null, true, true));
        ])->withCookie(Cookie::make('access_token', $token->plainTextToken, 60, '/', '192.168.7.66', true, true, false, 'None'));
        // ])->withCookie(Cookie::make('access_token', $token->plainTextToken, 60, '/', '192.168.7.66', false, false));

        // return and GENERATE TOKEN
        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API token of ' . $user->username)->plainTextToken
        ]);
    }

    // public function logout()
    // {

    //     Auth::user()->currentAccessToken()->delete();

    //     return $this->success([
    //         'message' => 'You have successfully logged out'
    //     ]);
    // }

    public function logout(Request $r)
    {

        // Auth::user()->currentAccessToken()->delete();
        $r->user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully logged out'
        ]);
    }

    public function getUserInfo(Request $r)
    {


        $result = DB::select("select * from hospAPI.dbo.getUserInfo(?)", [$r->user()->name]);

        $user_access = DB::table('users_access as ua')
            ->select('s.systemid', 's.systemdesc', 'al.accessid', 'al.accessdesc')
            ->join('systems as s', 'ua.systemid', '=', 's.systemid')
            ->join('access_level as al', 'ua.access_levelid', '=', 'al.accessid')
            ->where('ua.user_name', $r->user()->name)
            ->get();
        $result[0]->user_access = $user_access;





        return count($result) > 0 ? $result[0] : $this->error('', `hospital.dbo.user_acc: User '$r->user()->name' does not exist`, 500);
    }

    public function register(StoreUserRequest $request)
    {

        return User::create([
            'name' => $request->username,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);
    }
}
