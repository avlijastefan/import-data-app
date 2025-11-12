<?php

namespace App\Listeners;

use App\Events\ImportFailed;
use App\Mail\ImportFailureNotification;
use Illuminate\Support\Facades\Mail;

class SendImportFailureEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ImportFailed $event) {
        Mail::to('admin@example.com')
            ->send(new ImportFailureNotification($event->import));
    }
}
