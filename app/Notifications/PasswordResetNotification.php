<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Config;

class PasswordResetNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public string $url, )
    {

        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $expireMinutes = Config::get('auth.passwords.users.expire');
        return (new MailMessage)
            ->subject(__('Cambio de contraseña'))

            ->line(__('Estás recibiendo este correo electrónico porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta.'))
            ->action(__('Restablecer contraseña'), $this->url)
            ->line(__('Este enlace de restablecimiento de contraseña caducará en :minutes minutos.', ['minutes' => $expireMinutes]))
            ->line(__('Si no solicitaste un restablecimiento de contraseña, no se requiere ninguna otra acción.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
