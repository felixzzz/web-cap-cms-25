<?php
namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactusEmail extends Mailable
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
        return $this->view('emails.contact')
            ->with([
                'firstName' => $this->data['firstname'],
                'lastName' => $this->data['lastname'] ?? '',
                'email' => $this->data['email'],
                'topic' => $this->topic->name,
                'country' => $this->data['country'] ?? '',
                'contactus' => $this->data['message'] ?? '',
            ])
            ->subject('Contact Us Form Submission '.$this->topic['name']);
    }
}
