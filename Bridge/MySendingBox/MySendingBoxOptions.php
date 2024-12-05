<?php

namespace Symfony\Component\Notifier\Bridge\MySendingBox;

use Symfony\Component\Notifier\Message\MessageOptionsInterface;
use Symfony\Component\Notifier\Recipient\PostalAddress;

class MySendingBoxOptions implements MessageOptionsInterface
{
    private array $options;

    public function __construct(
        PostalAddress $senderPostalAddress
    ){
        $this->options = [
            'sender' => [
                'company' => $senderPostalAddress->name,
                'address_line1' => $senderPostalAddress->streetAddress,
                'address_city' => $senderPostalAddress->city,
                'address_postalcode' => $senderPostalAddress->postalCode,
                'address_country' => $senderPostalAddress->country,
            ]
        ];
    }

    public function toArray(): array
    {
        return array_filter($this->options);
    }

    public function getRecipientId(): ?string
    {
        return null;
    }
}
