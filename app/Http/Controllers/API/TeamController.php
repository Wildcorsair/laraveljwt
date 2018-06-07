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
    public $notFoundStatus = 404;

    public function index() {
        $team = Team::all();
        return response()->json(['success' => 'ok', 'dataset' => $team], $this->sucessStatus);
    }

    public function store(Request $request) {
        $this->validate($request, array(
            'firstName' => 'required|max:32',
            'lastName' => 'required|max:32',
            'teamCategory' => 'required',
            'teamPosition' => 'required',
            'linkedin' => 'max:254',
            'facebook' => 'max:254',
            'twitter' => 'max:254',
            'github' => 'max:254',
            'stackOverflow' => 'max:254'
        ));

        $member = new Team();
        $member->first_name = $request->get('firstName');
        $member->last_name = $request->get('lastName');
        $member->team_category = $request->get('teamCategory');
        $member->team_position = $request->get('teamPosition');
        $member->linkedin = $request->get('linkedin');
        $member->facebook = $request->get('facebook');
        $member->twitter = $request->get('twitter');
        $member->github = $request->get('github');
        $member->stack_overflow = $request->get('stackOverflow');
        $member->description = $request->get('description');

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $fileName);
            Image::make($image)->fit(165)->crop(165, 165)->save($location);
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
            'twitter' => 'max:254',
            'github' => 'max:254',
            'stackOverflow' => 'max:254'
        ));

        $member->first_name = $request->get('firstName');
        $member->last_name = $request->get('lastName');
        $member->team_category = $request->get('teamCategory');
        $member->team_position = $request->get('teamPosition');
        $member->linkedin = $request->get('linkedin');
        $member->facebook = $request->get('facebook');
        $member->twitter = $request->get('twitter');
        $member->github = $request->get('github');
        $member->stack_overflow = $request->get('stackOverflow');
        $member->description = $request->get('description');

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $fileName = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $fileName);
            Image::make($image)->fit(165)->crop(165, 165)->save($location);
            $member->photo = $fileName;
        } else if ($request->get('photo') == 'null') {
            $member->photo = null;
        }

        $member->save();

        return response()->json(['success' => 'updated', 'record' => $member], $this->sucessStatus);
    }

    public function destroy(Request $request, $id) {
        $member = Team::find($id);

        if (!is_null($member)) {
            $member->delete();

            return response()->json(['success' => 'deleted'], $this->sucessStatus);
        }

        return response()->json(['success' => 'error'], $this->notFoundStatus);
    }
}
