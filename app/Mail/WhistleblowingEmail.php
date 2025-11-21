<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class WhistleblowingEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $topic;

    public function __construct($data, $topic)
    {
        $this->data = $data;
        $this->topic = $topic;
    }

    public function build()
    {
        return $this->view('emails.whistleblowing')
            ->with([
                'firstName' => $this->data['firstname'],
                'lastName' => $this->data['lastname'] ?? '',
                'email' => $this->data['email'],
                'topic' => $this->topic->name,
                'country' => $this->data['country'] ?? '',
                'whistleblowing' => (string) ($this->data['message'] ?? ''),
            ])
            ->subject('Whistleblowing Form Submission '.$this->topic->name);
    }
}
