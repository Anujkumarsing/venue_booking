<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class SubAdminLoginInfoMail extends Mailable
{
    use Queueable, SerializesModels;
    public $subAdmin;
    public $password;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct($subAdmin, $password)
    {
        $this->subAdmin = $subAdmin;
        $this->password = $password;
        $this->loginUrl = route('admin.login');
    }

    /**
     * Get the message envelope.
     */
    public function build()
    {
        return $this->view('admin.email.subadmin_login_info')
        ->subject('Sub Admin Login Info')
        ->with([
           'subAdmin' => $this->subAdmin,
           'password' => $this->password,
           'loginUrl' => $this->loginUrl,
        ]);
    }
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Sub Admin Login Info',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.email.subadmin_login_info',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
