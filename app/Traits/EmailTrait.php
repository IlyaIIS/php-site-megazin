<?php

namespace App\Traits;

use Illuminate\Support\Facades\Mail;

trait EmailTrait {
    private function sendEmail(string $targetEmail, string $text) {
        Mail::send('mail', ['text' => $text], function($message) use ($targetEmail) {
            $message->to($targetEmail)->subject('Notification');
            $message->from('megazinmailer@gmail.com');
        });
    }
}
