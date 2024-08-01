<?php

namespace Modules\Culqi\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CulqiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    public function __construct($document)
    {
        $this->document = $document;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Constancia - Nuevo pago desde Ecommerce')
                    ->from(config('mail.username'), 'Constancia de Pago')
                    ->view('tenant.templates.email.culqui_payment');
    }
}
