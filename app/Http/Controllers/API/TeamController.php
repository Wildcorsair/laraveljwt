<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;

class TeamController extends Controller
{

    public $sucessStatus = 200;

    public function index() {
        $team = Team::all();
        return response()->json(['success' => 'ok', 'dataset' => $team], $this->sucessStatus);
    }

    public function edit($id) {
        $member = Team::find($id);
        return response()->json(['success' => 'ok', 'record' => $member], $this->sucessStatus);
    }
}
