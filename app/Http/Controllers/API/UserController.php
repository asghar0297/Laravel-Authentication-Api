<?php
namespace App\Http\Controllers\API;
use DB;
use App\User; 
use Validator;
use App\Device;
use App\Vendor;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\Auth; 

class UserController extends Controller 
{
public $successStatus = 200;
        // 202 Accepted
        // 204 No Content
        // 400 Bad Request
        // 404 Not Found


    public function login(){ 
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){ 
            $user = Auth::user(); 

            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            $success['name'] =  $user->email;
            $success['password'] =  $user->password;
        
            return response()->json(['success' => $success], $this-> successStatus); 
        } 
        else{ 
            return response()->json(['error'=>'Unauthorised'], 401); 
        } 
    }

    public function register(Request $request) 
    { 
        $validator = Validator::make($request->all(), [ 
            'name' => 'required', 
            'email' => 'required|email', 
            'password' => 'required', 
            'c_password' => 'required|same:password', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
            $input = $request->all(); 
            $input['password'] = bcrypt($input['password']); 
            $user = User::create($input); 
            $success['token'] =  $user->createToken('MyApp')-> accessToken; 
            $success['name'] =  $user->name;
        return response()->json(['success'=>$success], $this-> successStatus); 
    }

    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], $this-> successStatus); 
    } 

    public function getVendor() 
    { 
        $user = Auth::user();

        $vendors =Vendor::where('status',1)->get();
        $success['vendors'] = $vendors;
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        return response()->json(['success' => $success], $this-> successStatus); 
    }

    public function getDevice() 
    { 
        $user = Auth::user();
        $devices = $user->Devices;
        $success['devices'] = $devices;
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        return response()->json(['success' => $success], $this-> successStatus); 
    }

    public function getDeviceById($id) 
    { 
        $user = Auth::user();
        $devices = Device::find($id);
            $success['devices'] = $devices;
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this-> successStatus); 
    }

    public function getPolicy() 
    { 
        $user = Auth::user();
        $policy = DB::table('company_policy')->where('created_by',$user->id)->get();
        if(count($policy) > 0){

            $success['policy'] = $policy;
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            return response()->json(['success' => $success], $this-> successStatus); 
        }
        else{
            return response('No Record Found', 204);

        }
    }
    
    public function logoutApi()
    { 
        if (Auth::check()) {
        Auth::user()->AauthAcessToken()->delete();
        }
    }

    public function create_device(Request $request) 
    { 
        $user = Auth::user();
        $validator = Validator::make($request->all(), [ 
            'device_name' => 'required', 
            'device_vendor' => '', 
            'ip' => 'required', 
            'port' => 'required', 
            'user_id' => 'required', 
            'password' => 'required', 
        ]);

        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
            $input = $request->all();
            $input['created_by'] = $user->id;
            $input['password'] =  bcrypt($input['password']); 
            $input['status'] = 1;
            $device = Device::create($input); 
            $success['device'] =  $device; 
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
        return response()->json(['success'=>$success], $this->successStatus); 
    }

    public function update_device(Device $device, request $request) 
    { 
        $user = Auth::user();
        $validator = Validator::make($request->all(), [ 
            'device_name' => 'required', 
            'device_vendor' => '', 
            'ip' => 'required', 
            'port' => 'required', 
        ]);

        
        if ($validator->fails()) { 
            return response()->json(['error'=>$validator->errors()], 401);            
        }
        
            $input = $request->all();
            $device->update($input); 
            $success['device'] =  $device; 
            $success['token'] =  $user->createToken('MyApp')->accessToken; 
        return response()->json(['success'=>$success], $this->successStatus); 
    }

    public function delete_device(Device $device){

        $device->delete();
        return response()->json(['Success' => 'Device Deleted Successfully'],$this->successStatus);


    }
}