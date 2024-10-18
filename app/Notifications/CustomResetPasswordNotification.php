<?php

namespace App\Notifications;


use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;
    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Recuperación de Contraseña')
            ->line('Estás recibiendo este correo porque recibimos una solicitud de recuperación de contraseña.')
            ->action('Restablecer Contraseña', url(route('password.reset', $this->token, false)))
            ->line('Si no solicitaste un restablecimiento, ignora este correo.');
    }
}
