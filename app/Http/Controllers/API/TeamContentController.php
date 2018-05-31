<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class TeamContentController extends Controller
{

    public $sucessStatus = 200;
    public $notFoundStatus = 404;

    public function index() {
        $content = Page::where('page', 'team-content')->first();

        return response()->json(['success' => 'ok', 'record' => $content], $this->sucessStatus);
    }

    public function store(Request $request) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $content = new Page();
        $content->page = $request->get('page');
        $content->content = $request->get('content');
        $content->status = $request->get('status');
        $content->save();

        return response()->json(['success' => 'created', 'record' => $content], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $content = Page::find($id);
        $content->page = $request->get('page');
        $content->content = $request->get('content');
        $content->status = $request->get('status');
        $content->save();

        return response()->json(['success' => 'updated', 'record' => $content], $this->sucessStatus);
    }
}
