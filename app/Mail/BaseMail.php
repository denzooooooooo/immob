<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

abstract class BaseMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $data;

    /**
     * Créer une nouvelle instance de mail.
     */
    public function __construct(User $user, $data = [])
    {
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Construire le message.
     */
    public function build()
    {
        return $this->subject($this->getSubject())
                    ->view($this->getView())
                    ->with([
                        'user' => $this->user,
                        'data' => $this->data,
                        'appName' => config('app.name'),
                        'appUrl' => config('app.url'),
                        'supportEmail' => config('mail.support_email', 'support@monnkama.ga'),
                        'year' => date('Y')
                    ]);
    }

    /**
     * Obtenir le sujet du mail.
     */
    abstract protected function getSubject(): string;

    /**
     * Obtenir la vue du mail.
     */
    abstract protected function getView(): string;
}
