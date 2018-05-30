<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class ContactController extends Controller
{

    public $sucessStatus = 200;
    public $notFoundStatus = 404;

    public function index() {
        $contact = Page::where('page', 'contact')->first();

        return response()->json(['success' => 'ok', 'record' => $contact], $this->sucessStatus);
    }

    public function store(Request $request) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $contact = new Page();
        $contact->page = $request->get('page');
        $contact->content = $request->get('content');
        $contact->status = $request->get('status');
        $contact->save();

        return response()->json(['success' => 'created', 'record' => $contact], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $contact = Page::find($id);
        $contact->page = $request->get('page');
        $contact->content = $request->get('content');
        $contact->status = $request->get('status');
        $contact->save();

        return response()->json(['success' => 'updated', 'record' => $contact], $this->sucessStatus);
    }
}
