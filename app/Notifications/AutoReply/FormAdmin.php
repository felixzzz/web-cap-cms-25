<?php

namespace App\Notifications\AutoReply;

use App\Models\Form\Form;
use App\Models\Form\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class FormAdmin extends Notification implements ShouldQueue
{
    use Queueable;

    protected $form;
    protected $submission;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Form $form, Submission $submission)
    {
        $this->form = $form;
        $this->submission = $submission;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $arr = [];
        foreach ($this->submission->fields as $item) {
            $arr[] = '<li>'.$item->key.': <b>'.$item->value.'</b></li>';
        }
        $val = implode('', $arr);
        return (new MailMessage)
                    ->subject('Form Submission - '.$this->form->name)
                    ->line('There are new incoming submission for '.$this->form->name. '.  These are the details of the submission:')
                    ->line(new HtmlString('<ul>'.$val.'</ul>'))
                    ->line('For more details you can search the submission in the list with date '.$this->submission->created_at)
                    ->action('Submission List', route('admin.form.submission', $this->form));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
