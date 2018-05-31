<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;

class HomeContentController extends Controller
{

    public $sucessStatus = 200;
    public $notFoundStatus = 404;

    public function index() {
        $content = Page::where('page', 'home')->first();

        return response()->json(['success' => 'ok', 'record' => $content], $this->sucessStatus);
    }
}
