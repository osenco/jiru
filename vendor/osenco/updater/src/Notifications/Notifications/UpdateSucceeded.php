<?php

declare(strict_types=1);

namespace Osen\Updater\Notifications\Notifications;

use Osen\Updater\Events\UpdateSucceeded as UpdateSucceededEvent;
use Osen\Updater\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

final class UpdateSucceeded extends BaseNotification
{
    /**
     * @var UpdateSucceededEvent
     */
    protected $event;

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->from(config('updater.notifications.mail.from.address', config('mail.from.address')), config('updater.notifications.mail.from.name', config('mail.from.name')))
            ->subject(config('app.name').': Update succeeded');
    }

    public function setEvent(UpdateSucceededEvent $event)
    {
        $this->event = $event;

        return $this;
    }
}
