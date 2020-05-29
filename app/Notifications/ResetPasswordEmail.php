<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;

class ResetPasswordEmail extends ResetPasswordBase
{
    public function toMail($notifiable)
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable);
        }

        return (new MailMessage())
            ->subject(__('passwords.mail.subject'))
            ->line(__('passwords.mail.line1'))
            ->action(
                __('passwords.mail.action'),
                url(
                    config('app.url') . route('password.reset', [
                        'token' => $this->token,
                        'email' => $notifiable->getEmailForPasswordReset()
                    ], false)
                )
            )
            ->line(__('passwords.mail.line2', [
                'count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')
            ]))
            ->line(__('passwords.mail.line3'));
    }
}
