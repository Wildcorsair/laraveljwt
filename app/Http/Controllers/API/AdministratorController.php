<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

class AdministratorController extends Controller
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
        if (!$user->can('administrator-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $administrators = User::where('type', 'administrator')->paginate(5);
        return response()->json(['success' => 'ok', 'paginator' => $administrators], $this->sucessStatus);
    }

    public function store(Request $request) {
        $user = Auth::user();
        if (!$user->can('administrator-create')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'status' => 'required'
        ]);

        $administrator = new User();
        $administrator->name = $request->get('firstName') . ' ' . $request->get('lastName');
        $administrator->email = $request->get('email');
        $administrator->password = bcrypt($request->get('password'));
        $administrator->type = 'administrator';
        $administrator->is_active = $request->get('status');
        $administrator->save();

        $premissions = $request->get('permissions');

        if ($administrator) {
            $administrator->assignRole('administrator');
        }

        if ($administrator && is_array($premissions)) {
            $activePermissions = [];
            foreach ($premissions as $key => $value) {
                if ($value) {
                    $activePermissions[] = $key;
                }
            }

            $administrator->givePermissionTo($activePermissions);
        }

        return response()->json(['success' => 'created', 'record' => $administrator], $this->sucessStatus);
    }

    public function edit($id) {
        $user = Auth::user();
        if (!$user->can('administrator-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }

        $administrator = User::find($id);
        $administrator->permissions;

        return response()->json(['success' => 'ok', 'record' => $administrator], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $user = Auth::user();
        if (!$user->can('administrator-update')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }
        $this->validate($request, [
            'firstName' => 'required',
            'lastName' => 'required',
            // 'email' => 'email|unique:users',
            'status' => 'required'
        ]);

        $administrator = User::find($id);

        if ($administrator) {
            $administrator->name = $request->get('firstName') . ' ' . $request->get('lastName');
            if (!empty($request->get('email')) && $administrator->email !== $request->get('email')) {
                $administrator->email = $request->get('email');
            }
            if (!empty($request->get('password'))) {
                $administrator->password = bcrypt($request->get('password'));
            }
            $administrator->is_active = $request->get('status');
            $administrator->save();
        }

        $premissions = $request->get('permissions');

        if ($administrator && is_array($premissions)) {
            $activePermissions = [];
            foreach ($premissions as $key => $value) {
                if ($value) {
                    $activePermissions[] = $key;
                }
            }

            $administrator->syncPermissions($activePermissions);
        }

        return response()->json(['success' => 'updated', 'record' => $administrator], $this->sucessStatus);
    }

    public function destroy($id) {
        $user = Auth::user();
        if (!$user->can('administrator-delete')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }
        $administrator = User::find($id);
        $administrator->delete();

        return response()->json(['success' => 'deleted'], $this->sucessStatus);
    }
}
