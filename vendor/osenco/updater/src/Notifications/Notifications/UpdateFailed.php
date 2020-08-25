<?php

declare(strict_types=1);

namespace Osen\Updater\Notifications\Notifications;

use Osen\Updater\Events\UpdateFailed as UpdateFailedEvent;
use Osen\Updater\Notifications\BaseNotification;
use Illuminate\Notifications\Messages\MailMessage;

final class UpdateFailed extends BaseNotification
{
    /**
     * @var UpdateFailedEvent
     */
    protected $event;

    public function toMail(): MailMessage
    {
        return (new MailMessage())
            ->from(config('updater.notifications.mail.from.address', config('mail.from.address')), config('updater.notifications.mail.from.name', config('mail.from.name')))
            ->subject(config('app.name').': Update failed');
    }

    public function setEvent(UpdateFailedEvent $event)
    {
        $this->event = $event;

        return $this;
    }
}
