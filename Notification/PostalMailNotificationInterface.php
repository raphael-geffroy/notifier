<?php

namespace Symfony\Component\Notifier\Notification;

use Symfony\Component\Notifier\Message\PostalMailMessage;
use Symfony\Component\Notifier\Recipient\PostalMailRecipientInterface;

/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
interface PostalMailNotificationInterface
{
    public function asPostalMailMessage(
        PostalMailRecipientInterface $recipient,
        ?string $transport = null
    ): ?PostalMailMessage;
}
