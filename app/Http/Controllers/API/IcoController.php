<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class IcoController extends Controller
{
    public $sucessStatus = 200;
    public $notFoundStatus = 404;

    public function index() {
        $ico = Page::where('page', 'ico')->first();

        return response()->json(['success' => 'ok', 'record' => $ico], $this->sucessStatus);
    }

    public function store(Request $request)
    {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $ico = new Page();
        $ico->page = $request->get('page');
        $ico->content = $request->get('content');
        $ico->status = $request->get('status');
        $ico->save();

        return response()->json(['success' => 'created', 'record' => $ico], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $ico = Page::find($id);
        $ico->page = $request->get('page');
        $ico->content = $request->get('content');
        $ico->status = $request->get('status');
        $ico->save();

        return response()->json(['success' => 'updated', 'record' => $ico], $this->sucessStatus);
    }
}
