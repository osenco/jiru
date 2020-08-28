<?php

namespace App\Notifications;

use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\AfricasTalking\AfricasTalkingChannel;
use NotificationChannels\AfricasTalking\AfricasTalkingMessage;

class SendAdminTextViaAT extends Notification
{
    use Queueable;

    public $payment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [AfricasTalkingChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toAfricasTalking($notifiable)
    {
        $message           = settings('notifications_sms', 'after_sale_sms_msg', 'Hi {user}. {company} appreciates your payment of KSH {amount}.');
        $formatted_message = trim($message);
        $formatted_message = str_replace('{user}', $notifiable->name, $formatted_message);

        if (!empty($data)) {
            foreach ($data as $item => $value) {
                $formatted_message = str_replace("{" . $item . "}", $value, $formatted_message);
            }
        }

        $customized_message = str_replace('{company}', settings("general", "name", config("app.name", "Pessa")), $formatted_message);

        if (settings('notifications_sms', 'after_sale_feedback', 'no') == 'yes') {
            $feedback = str_replace('{phone}', settings("general", "phone", "0204404993"), settings('notifications_sms', 'after_sale_feedback_msg'));
            $customized_message .= " {$feedback}";
        }
        return (new AfricasTalkingMessage())
            ->content($customized_message);
    }
}
