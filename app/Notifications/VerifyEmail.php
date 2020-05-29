<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;

class VerifyEmail extends VerifyEmailBase
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage())
            ->subject(__('verify_email.mail.subject'))
            ->line(__('verify_email.mail.message'))
            ->action(
                __('verify_email.mail.subject'),
                $this->verificationUrl($notifiable)
            )
            ->line(__('verify_email.mail.footer'));
    }
}
