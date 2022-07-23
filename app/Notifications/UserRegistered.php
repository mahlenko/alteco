<?php

namespace App\Notifications;

use DateTimeImmutable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;

class UserRegistered extends Notification
{
    use Queueable;

    private string $password_text;
    private DateTimeImmutable $expired;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(string $password, DateTimeImmutable $expired)
    {
        $this->password_text = $password;
        $this->expired = $expired;
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
        $expired = Carbon::make($this->expired);

        return (new MailMessage)
                    ->line('Ваш логин: ' . $notifiable->email,)
                    ->line('Пароль: ' . $this->password_text)
                    ->line('Доступ открыт до: ' . $expired->isoFormat('DD MMMM YYYY H:m'))
                    ->line('Вы можете изменить пароль в личном кабинете в любое время.')
                    ->action('Перейти на сайт', url('/'));
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
