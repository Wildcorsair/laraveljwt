<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Support\Facades\Mail;

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
            'title' => 'required|max:254',
            'status' => 'required|max: 20',
            'recipientEmail' => 'required|email'
        ));

        $content = json_encode([
          'title' => $request->get('title'),
          'text' => $request->get('contactPageText'),
          'code' => $request->get('mapURL'),
          'recipientEmail' => $request->get('recipientEmail')
        ]);

        $contact = new Page();
        $contact->page = $request->get('page');
        $contact->content = $content;
        $contact->status = $request->get('status');
        $contact->save();

        return response()->json(['success' => 'created', 'record' => $contact], $this->sucessStatus);
    }

    public function update(Request $request, $id) {
        $this->validate($request, array(
            'page' => 'required|max:20',
            'title' => 'required|max:254',
            'status' => 'required|max: 20',
            'recipientEmail' => 'required|email'
        ));

        $content = json_encode([
          'title' => $request->get('title'),
          'text' => $request->get('contactPageText'),
          'code' => $request->get('mapURL'),
          'recipientEmail' => $request->get('recipientEmail'),
        ]);

        $contact = Page::find($id);
        $contact->page = $request->get('page');
        $contact->content = $content;
        $contact->status = $request->get('status');
        $contact->save();

        return response()->json(['success' => 'updated', 'record' => $contact], $this->sucessStatus);
    }

    public function sendMessage(Request $request) {
      $contact = [
        'username' => $request->get('name'),
        'email' => $request->get('email'),
        'residence' => $request->get('residence'),
        'contactMessage' => $request->get('message'),
        // 'profile' => $request->get('profile')
      ];

      Mail::send('emails.contact', $contact, function($message) {
        $message->to('success@simulator.amazonses.com', 'support')->from('success@simulator.amazonses.com')->subject('New Contact Form Message');
      });

      return response()->json(['success' => 'ok', 'record' => $contact], $this->sucessStatus);
    }
}
