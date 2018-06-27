<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Hash;
use Validator;
use App\User;

class ProfileController extends Controller
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
        $customer = Auth::user();
        return response()->json(['success' => 'ok', 'record' => $customer], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $customer = User::find($id);

        if ($request->get('profile') == 'personal') {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required',
                'lastName' => 'required',
                'phone' => 'required'
            ]);

            if($validator->fails()) {
                return response()->json(['success' => 'error', 'message' => $validator->errors()], $this->badRequest);
            }

            $customer->name = $request->get('firstName') . ' ' . $request->get('lastName');
            $customer->phone = $request->get('phone');
            $customer->investor_type = $request->get('investorType')['investor'];
            $customer->save();
        }

        if ($request->get('profile') == 'security') {
            $validator = Validator::make($request->all(), [
                'oldPassword' => 'required',
                'password' => 'required',
                'confirmPassword' => 'required|same:password'
            ]);

            if($validator->fails()) {
                return response()->json(['success' => 'error', 'message' => 'Password and Password Confirm are not match.'], $this->badRequest);
            }

            if (!Hash::check($request->get('oldPassword'), $customer->password)) {
                return response()->json([
                    'success' => 'error',
                    'message' => 'Invalid old password.'
                ], $this->badRequest);
            }

            if ($request->get('password') != $request->get('confirmPassword')) {
                return response()->json([
                    'success' => 'error',
                    'message' => 'Password and Password Confirm are not match.'
                ], $this->badRequest);
            }

            $customer->password = bcrypt($request->get('password'));
            $customer->save();

            return response()->json([
                'success' => 'updated',
                'record' => $customer,
                'message' => 'Your password was successfully updated!'
            ], $this->sucessStatus);
        }

        if ($request->get('profile') == 'location') {
            $validator = Validator::make($request->all(), [
                'country' => 'required',
                'address1' => 'required',
                'address2' => 'required',
                'city' => 'required',
                'postalCode' => 'required'
            ]);

            if($validator->fails()) {
                return response()->json(['success' => 'error', 'message' => $validator->errors()], $this->badRequest);
            }

            $customer->country = $request->get('country');
            $customer->address1 = $request->get('address1');
            $customer->address2 = $request->get('address2');
            $customer->city = $request->get('city');
            $customer->postal_code = $request->get('postalCode');
            $customer->save();
        }

        if ($customer) {
            return response()->json(['success' => 'updated', 'record' => $customer], $this->sucessStatus);
        }

        return response()->json(['error' => $validator->errors()], $this->badRequest);
    }
}
