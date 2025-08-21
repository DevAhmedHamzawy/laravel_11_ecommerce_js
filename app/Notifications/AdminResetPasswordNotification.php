<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class AdminResetPasswordNotification extends Notification
{
    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = route('admin.reset-password', ['token' => $this->token, 'email' => $notifiable->email]);

        return (new MailMessage)
                    ->subject(trans('forgot_password.reset_password'))
                    ->line(trans('forgot_password.you_requested_a_password_reset_link'))
                    ->action(trans('forgot_password.reset_password'), $url)
                    ->line(trans('forgot_password.if_you_didnt_request_a_password_reset_link', ['name' => config('app.name')]));
    }
}
