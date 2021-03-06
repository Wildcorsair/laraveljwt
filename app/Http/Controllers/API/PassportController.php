<?php
namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Services\SendMail;

class PassportController extends Controller
{
    // // 20*
    // public $sucessStatus = 200;
    // // 40*
    // public $badRequest = 400;
    // public $unauthorized = 401;
    // public $forbidden = 403;
    // // 50*
    // public $unknownError = 520;


    protected $mailer;

    public function __construct(SendMail $mailer)
    {
        $this->mailer = $mailer;
    }

    /*
     * login api
     *
     * @return \Illuminate\Http\Response
     */

    public function login() {
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])) {
            $user = Auth::user();
            if ($user->is_active == true) {
                $success['token'] = $user->createToken('MyApp')->accessToken;
                $success['name'] = $user->name;
                $success['type'] = $user->type;
                $success['investor_id'] = $user->investor_id;
                return response()->json(['success' => $success], $this->sucessStatus);
            } else {
                return response()->json(['error' => 'Non-activated'], $this->forbidden);
            }
        }
        else {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
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
            return response()->json(['error' => $validator->errors()], $this->badRequest);
        }

        // $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        // $user = User::create($input);

        $user = new User();
        $user->name = $request->get('firstName') . ' '. $request->get('lastName');
        $user->email = $request->get('email');
        $user->password = bcrypt($request->get('password'));
        $user->country = $request->get('country');
        $user->investor_id = $this->generateInvestorId(20);
        $user->phone = $request->get('phone');
        $user->address1 = $request->get('address1');
        $user->address2 = $request->get('address2');
        $user->city = $request->get('city');
        $user->postal_code = $request->get('postalCode');
        $user->investor_type = $request->get('investorType');
        $user->tokens_count = $request->get('tokensCount');
        $user->type = 'customer';
        $user->remember_token = str_random(32);
        $user->save();

        // Assign role 'customer' for just created user
        if ($user) {
          $user->assignRole('customer');
        }

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'token' => $user->remember_token
        ];

        // Send email message for user with activation link
        $this->mailer->sendMessage($data);

        // $success['token'] = $user->createToken('MyApp')->accessToken;
        $success['name'] = $user->name;

        return response()->json(['success' => 'ok', 'record' => $success], $this->sucessStatus);
    }

    /**
     * User token verification.
     */
    public function permit(Request $request) {
        $validator = Validator::make($request->all(), [
            'permission' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], $this->badRequest);
        }

        $user = Auth::user();
        if ($user->can($request->permission)) {
            return response()->json(['success' => 'verified', 'user' => $user->name], $this->sucessStatus);
        }
        return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
    }

    /**
     * User token verification.
     */
    public function verify() {
        return response()->json(['success' => 'verified'], $this->sucessStatus);
    }

    public function activate(Request $request) {
        $validator = Validator::make($request->all(), [
            'token' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], $this->badRequest);
        }

        $user = User::where('remember_token', $request->token)->first();
        if ($user) {
          $user->remember_token = null;
          $user->is_active = true;
          $user->save();

          return response()->json(['success' => 'ok'], $this->sucessStatus);
        }

        return response()->json(['success' => 'error'], $this->unknownError);
    }

    public function generateInvestorId($length = 12) {
    	$str = "";
    	$characters = array_merge(range('A','Z'), range('a','z'), range('0','9'));
    	$max = count($characters) - 1;
    	for ($i = 0; $i < $length; $i++) {
    		$rand = mt_rand(0, $max);
    		$str .= $characters[$rand];
    	}
    	return $str;
    }

}
