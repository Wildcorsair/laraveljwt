<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Page;
use App\ContactMessage;
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

      $contactMessage = new ContactMessage();
      $contactMessage->name = $request->name;
      $contactMessage->email = $request->email;
      $contactMessage->residence = $request->residence;
      $contactMessage->message = $request->message;
      $contactMessage->seed_investor = $request->profile['seedInvestor'];
      $contactMessage->service_provider = $request->profile['serviceProvider'];
      $contactMessage->retail_investor = $request->profile['retailInvestor'];
      $contactMessage->institutional = $request->profile['institutional'];
      $contactMessage->government = $request->profile['government'];
      $contactMessage->media = $request->profile['media'];
      $contactMessage->save();

      $mailMessage = [
        'name'             => $contactMessage->name,
        'email'            => $contactMessage->email,
        'residence'        => $contactMessage->residence,
        'text'             => $contactMessage->message,
        'seed_investor'    => $contactMessage->seed_investor,
        'service_provider' => $contactMessage->service_provider,
        'retail_investor'  => $contactMessage->retail_investor,
        'institutional'    => $contactMessage->institutional,
        'government'       => $contactMessage->government,
        'media'            => $contactMessage->media
      ];

      $contactPage = Page::where('page', 'contact')->first();
      $contactPageContent = json_decode($contactPage->content);
      $recipientMail = $contactPageContent->recipientEmail;

      Mail::send('emails.contact', (array) $mailMessage, function($message) use($recipientMail) {
        $message->to($recipientMail, 'Contacts')->from('admin@grip.investments')->subject('New Contact Form Message');
      });

      return response()->json(['success' => 'ok', 'record' => $contactMessage], $this->sucessStatus);
    }

    public function getContactMessages() {
      $messages = ContactMessage::orderBy('created_at', 'DESC')->paginate(5);
      return response()->json(['success' => 'ok', 'paginator' => $messages], $this->sucessStatus);
    }

    public function getContactMessage($id) {
      $message = ContactMessage::find($id);
      $message->status = true;
      $message->save();

      return response()->json(['success' => 'ok', 'record' => $message], $this->sucessStatus);

    }

    public function deleteContactMessage($id) {
      $message = ContactMessage::find($id);

      if (!is_null($message)) {
        $message->delete();
        return response()->json(['success' => 'deleted'], $this->sucessStatus);
      }

      return response()->json(['success' => 'error'], $this->$notFoundStatus);
    }
}
