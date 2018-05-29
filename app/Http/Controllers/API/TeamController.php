<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Team;
use Image;
use Log;

class TeamController extends Controller
{

    public $sucessStatus = 200;

    public function index() {
        $team = Team::all();
        return response()->json(['success' => 'ok', 'dataset' => $team], $this->sucessStatus);
    }

    public function store(Request $request) {
        Log::info($request);

        $this->validate($request, array(
            'firstName' => 'required|max:32',
            'lastName' => 'required|max:32',
            'teamCategory' => 'required',
            'teamPosition' => 'required',
            'linkedin' => 'max:254',
            'facebook' => 'max:254',
            'twitter' => 'max:254'
        ));

        $member = new Team();
        $member->first_name = $request->get('firstName');
        $member->last_name = $request->get('lastName');
        $member->team_category = $request->get('teamCategory');
        $member->team_position = $request->get('teamPosition');
        $member->linkedin = $request->get('linkedin');
        $member->facebook = $request->get('facebook');
        $member->twitter = $request->get('twitter');

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $fileName);
            Image::make($image)->resize(165, 165)->save($location);
            $member->photo = $fileName;
        }
        $member->save();

        return response()->json(['success' => 'created', 'record' => $member], $this->sucessStatus);
    }

    public function edit($id) {
        $member = Team::find($id);
        return response()->json(['success' => 'ok', 'record' => $member], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $member = Team::find($id);

        $this->validate($request, array(
            'firstName' => 'required|max:32',
            'lastName' => 'required|max:32',
            'teamCategory' => 'required',
            'teamPosition' => 'required',
            'linkedin' => 'max:254',
            'facebook' => 'max:254',
            'twitter' => 'max:254'
        ));

        $member->first_name = $request->get('firstName');
        $member->last_name = $request->get('lastName');
        $member->photo = $request->get('photo');
        $member->team_category = $request->get('teamCategory');
        $member->team_position = $request->get('teamPosition');
        $member->linkedin = $request->get('linkedin');
        $member->facebook = $request->get('facebook');
        $member->twitter = $request->get('twitter');
        $member->save();

        return response()->json(['success' => 'updated', 'record' => $member], $this->sucessStatus);
    }
}
