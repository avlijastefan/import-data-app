<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ImportFailureNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $import;
    public function __construct($import)
    {
        $this->import = $import;
    }

    public function build() 
    {
        return $this->subject('Import Failed: ' . config("imports.types.{$this->import->type}.label"))
                    ->view('emails.import_failed');
    }
}
