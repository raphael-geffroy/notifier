<?php

namespace Symfony\Component\Notifier\Message;

use Symfony\Component\Notifier\Message\Letter\RawLetter;
use Symfony\Component\Notifier\Notification\Notification;
use Symfony\Component\Notifier\Recipient\PostalAddress;
use Symfony\Component\Notifier\Recipient\PostalMailRecipientInterface;

/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
class PostalMailMessage implements MessageInterface, FromNotificationInterface
{
    private ?string $transport = null;
    private ?Notification $notification = null;

    public function __construct(
        private RawLetter $letter,
        private PostalAddress $recipientPostalAddress,
        private ?MessageOptionsInterface $options = null,
    ) {
    }

    public static function fromNotification(Notification $notification, PostalMailRecipientInterface $recipient): self
    {
        $message = new self(new RawLetter($notification->getSubject()), $recipient->getPostalAddress());
        $message->notification = $notification;

        return $message;
    }

    public function getLetter(): RawLetter
    {
        return $this->letter;
    }

    public function getSubject(): string
    {
        return $this->letter->content;
    }

    public function getRecipientId(): ?string
    {
        return $this->options?->getRecipientId();
    }

    public function getRecipientPostalAddress(): PostalAddress
    {
        return $this->recipientPostalAddress;
    }

    /**
     * @return $this
     */
    public function options(MessageOptionsInterface $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getOptions(): ?MessageOptionsInterface
    {
        return $this->options;
    }

    /**
     * @return $this
     */
    public function transport(?string $transport): static
    {
        $this->transport = $transport;

        return $this;
    }

    public function getTransport(): ?string
    {
        return $this->transport;
    }

    public function getNotification(): ?Notification
    {
        return $this->notification;
    }
}
