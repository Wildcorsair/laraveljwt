<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class HomeContentController extends Controller
{

    public $sucessStatus = 200;
    public $notFoundStatus = 404;

    /**
     * Return Home Page content.
     *
     * @return Response;
     */
    public function index() {
        $content = Page::where('page', 'home')->first();

        return response()->json(['success' => 'ok', 'record' => $content], $this->sucessStatus);
    }

    public function store(Request $request) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'content' => 'required',
            'status' => 'required|max: 20'
        ));

        $content = json_encode(array(
            'pageTitle' => $request->get('title'),
            'pageVideoUrl'  => $request->get('videoUrl'),
            'pageContent' => $request->get('content')
        ));

        $home = new Page();
        $home->page = $request->get('page');
        $home->content = $content;
        $home->status = $request->get('status');
        $home->save();

        return response()->json(['success' => 'created', 'record' => $home], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'status' => 'required|max: 20'
        ));


        $content = json_encode(array(
            'pageTitle' => $request->get('title'),
            'pageVideoUrl'  => $request->get('videoUrl'),
            'pageContent' => $request->get('content')
        ));

        $home = Page::find($id);
        $home->page = $request->get('page');
        $home->content = $content;
        $home->status = $request->get('status');
        $home->save();

        return response()->json(['success' => 'updated', 'record' => $home], $this->sucessStatus);
    }
}
