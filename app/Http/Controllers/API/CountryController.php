<?php

namespace App\Http\Controllers\API;

use App\Country;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CountryController extends Controller
{
    public function index() {
        $countries = Country::all();
        return response()->json(['success' => 'ok', 'dataset' => $countries], $this->sucessStatus);
    }
}
