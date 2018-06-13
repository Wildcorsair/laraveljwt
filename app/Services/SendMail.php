<?php
namespace App\Services;

use Illuminate\Support\Facades\Mail;

class SendMail {

  public function sendMessage($data) {
      // Add domain for email activation link
      $data['domain'] = config('app.url');

      Mail::send('emails.activation', $data, function($message) use ($data) {
          $message->to( $data['email'], $data['name'])->from('admin@grip.investments')->subject('Account activation');
      });
  }

}
