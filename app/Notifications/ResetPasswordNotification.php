<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Redefinição de Senha - MindSeat')
            ->line('Você solicitou redefinir sua senha.')
            ->action(
                'Redefinir Senha',
                url(route('password.reset', ['token' => $this->token, 'email' => $notifiable->email], false))
            )
            ->line('Se você não solicitou, ignore este email.');
    }
}