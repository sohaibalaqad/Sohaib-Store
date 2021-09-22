<?php

namespace App\Notifications;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        // mail, database, nexmo (SMS), broadcast, slack, [Custom channel]
        $via = [
            'database',
//            FcmChannel::class,
            'mail', 'broadcast',
//            'nexmo'
            //TweetSmsChannel::class
        ];
//        if ($notifiable->notify_sms) {
//            $via[] = 'nexmo';
//        }
//        if ($notifiable->notify_mail) {
//            $via[] = 'mail';
//        }
        return $via;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject(__('New order #:number', [
                        'number' => $this->order->number,
                    ]))
                    ->from('billing@localhost', 'SOHAIB store Billing')
                    ->greeting(__('Hello, :name', [
                        'name' => $notifiable->name ?? '',
                    ]))
                    ->line(__('A new order has been created (order#:number).', [
                        'number' => $this->order->number,
                    ]))
                    ->action(__('view order'), url('/'))
                    ->line('Thank you for shopping with us!')
                    /*->view('', [
                        'order' => $this->order,
                    ])*/;
    }

    /**
     * Get the database representation of the notification.
     *
     * @param $notifiable
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => __('New order #:number', ['number' => $this->order->number]),
            'body' => __('A new order has been created (order#:number).', [
                'number' => $this->order->number,
            ]),
            'icon' => '',
            'url' => url('/'),
        ];
    }

    /**
     * Get the b roadcast representation of the notification.
     *
     * @param $notifiable
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'title' => __('New order #:number', ['number' => $this->order->number]),
            'body' => __('A new order has been created (order#:number).', [
                'number' => $this->order->number,
            ]),
            'icon' => '',
            'url' => url('/'),
            'time' => Carbon::now()->diffForHumans(),
        ]);
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
