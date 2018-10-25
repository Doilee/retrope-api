<?php

namespace App\Notifications;

use App\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubscriptionExpired extends Notification
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new notification instance.
     *
     * @param $subscription
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
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
     * @param $notifiable
     *
     * @return MailMessage
     * @internal param Subscription $subscription
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('Your ' . $this->subscription->type . ' subscription has expired.')
                    ->line('If you wish to resume your subscription, please click on the link below.')
                    ->action('See Profile', url(config('frontend.url')))
                    ->line('Thank you for using our application, we hope to see you again in the (near) future!');
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
