<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Type;

class TypeController extends Controller
{
    public function index() {
        $types = Type::all();
        return response()->json(['success' => 'ok', 'dataset' => $types], $this->sucessStatus);
    }
}
