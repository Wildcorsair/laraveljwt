<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;
use Validator;

class CustomerController extends Controller
{

    // 20*
    public $sucessStatus = 200;
    // 40*
    public $badRequest = 400;
    public $unauthorized = 401;
    public $forbidden = 403;
    // 50*
    public $unknownError = 520;

    public function index() {
        $user = Auth::user();
        if (!$user->can('customer-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $customers = User::where('type', 'customer')->paginate(5);
        return response()->json(['success' => 'ok', 'paginator' => $customers], $this->sucessStatus);
    }

    public function store(Request $request) {
        $user = Auth::user();
        if (!$user->can('customer-create')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $validator = Validator::make($request->all(),[
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'investorType' => 'required',
            'status' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], $this->badRequest);
        }

        $customer = new User();
        $customer->name = $request->get('firstName') . ' '. $request->get('lastName');
        $customer->email = $request->get('email');
        $customer->password = bcrypt($request->get('password'));
        $customer->country = $request->get('country');
        $customer->is_active = $request->get('status');
        $customer->phone = $request->get('phone');
        $customer->address1 = $request->get('address1');
        $customer->address2 = $request->get('address2');
        $customer->city = $request->get('city');
        $customer->postal_code = $request->get('postalCode');
        $customer->investor_type = $request->get('investorType')['investor'];
        $customer->type = 'customer';
        $customer->save();

        $premissions = $request->get('permissions');
        // Assign role 'customer' for just created user
        if ($customer) {
          $customer->assignRole('customer');
        }

        if ($customer && is_array($premissions)) {
            $activePermissions = [];
            foreach ($premissions as $key => $value) {
                if ($value) {
                    $activePermissions[] = $key;
                }
            }

            $customer->givePermissionTo($activePermissions);
        }

        return response()->json(['success' => 'created', 'record' => $customer], $this->sucessStatus);
    }

    public function edit($id) {
        $user = Auth::user();
        if (!$user->can('customer-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $customer = User::find($id);
        $customer->permissions;

        return response()->json(['success' => 'ok', 'record' => $customer], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        if (!$user->can('customer-update')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $validator = Validator::make($request->all(),[
            'firstName' => 'required',
            'lastName' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'postalCode' => 'required',
            'investorType' => 'required',
            'status' => 'required'
        ]);

        if($validator->fails()) {
            return response()->json(['error' => $validator->errors()], $this->badRequest);
        }

        $customer =User::find($id);
        $customer->name = $request->get('firstName') . ' '. $request->get('lastName');

        if (!empty($request->get('email')) && $customer->email !== $request->get('email')) {
            $customer->email = $request->get('email');
        }

        if (!empty($request->get('password'))) {
            $customer->password = bcrypt($request->get('password'));
        }
        $customer->country = $request->get('country');
        $customer->is_active = $request->get('status');
        $customer->phone = $request->get('phone');
        $customer->address1 = $request->get('address1');
        $customer->address2 = $request->get('address2');
        $customer->city = $request->get('city');
        $customer->postal_code = $request->get('postalCode');
        $customer->investor_type = $request->get('investorType')['investor'];
        $customer->type = 'customer';
        $customer->save();

        $premissions = $request->get('permissions');

        if ($customer && is_array($premissions)) {
            $activePermissions = [];
            foreach ($premissions as $key => $value) {
                if ($value) {
                    $activePermissions[] = $key;
                }
            }

            $customer->syncPermissions($activePermissions);
        }

        return response()->json(['success' => 'updated', 'record' => $customer], $this->sucessStatus);
    }

    public function destroy($id) {
        $user = Auth::user();
        if (!$user->can('customer-delete')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }
        $customer = User::find($id);
        $customer->delete();

        return response()->json(['success' => 'deleted'], $this->sucessStatus);
    }
}
