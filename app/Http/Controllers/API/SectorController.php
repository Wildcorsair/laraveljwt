<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sector;

class SectorController extends Controller
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
        $sectors = Sector::all();
        return response()->json(['success' => 'ok', 'dataset' => $sectors], $this->sucessStatus);
    }
}
