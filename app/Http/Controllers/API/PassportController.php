<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;

class PassportController extends Controller
{
    public $sucessStatus = 200;

    /*
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login() {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            $success['token'] = $user->createToken('MyApp')->accessToken;
            $success['name'] = $user->name;
            return response()->json(['success' => $success], $this->sucessStatus);
        }
        else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    /*
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request) {
        $validator = Validator::make($request->all(),[
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'passwordConfirm' => 'required|same:password',
            'country' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'investorType' => 'required',
            'tokensCount' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()],401);
        }

        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $user = User::create($input);

        $user = new User();
        $user->name = $request->get('firstName') . ' '. $request->get('lastName');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->country = $request->get('country');
        $user->phone = $request->get('phone');
        $user->address1 = $request->get('address1');
        $user->address2 = $request->get('address2');
        $user->city = $request->get('city');
        $user->postal_code = $request->get('postalCode');
        $user->investor_type = $request->get('investorType');
        $user->tokens_count = $request->get('tokensCount');
        $user->save();

        // $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => 'ok', 'record' => $success], $this->sucessStatus);
    }

    public function verify() {
        return response()->json(['success' => 'verified'], $this->sucessStatus);
    }

}
