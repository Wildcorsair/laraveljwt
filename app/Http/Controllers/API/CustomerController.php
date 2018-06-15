<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

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
        if (!$user->can('administrator-read')) {
            return response()->json(['error' => 'Unauthorised'], $this->unauthorized);
        }
        $customers = User::where('type', 'customer')->paginate(5);
        return response()->json(['success' => 'ok', 'paginator' => $customers], $this->sucessStatus);
    }
}
