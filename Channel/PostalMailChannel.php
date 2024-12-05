<?php

namespace Symfony\Component\Notifier\Channel;

use Symfony\Component\Notifier\Message\PostalMailMessage;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Notification\PostalMailNotificationInterface;
use Symfony\Component\Notifier\Recipient\EmailRecipientInterface;
use Symfony\Component\Notifier\Recipient\PostalMailRecipientInterface;
use Symfony\Component\Notifier\Recipient\RecipientInterface;

/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
class PostalMailChannel extends AbstractChannel
{

    /**
     * @param PostalMailRecipientInterface $recipient
     */
    public function notify(Notification $notification, RecipientInterface $recipient, ?string $transportName = null): void
    {
        $message = null;
        if ($notification instanceof PostalMailNotificationInterface) {
            $message = $notification->asPostalMailMessage($recipient, $transportName);
        }

        $message ??= PostalMailMessage::fromNotification($notification, $recipient);

        if (null !== $transportName) {
            $message->transport($transportName);
        }

        if (null === $this->bus) {
            $this->transport->send($message);
        } else {
            $this->bus->dispatch($message);
        }
    }

    public function supports(Notification $notification, RecipientInterface $recipient): bool
    {
        return true;
    }
}
