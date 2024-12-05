<?php

namespace Symfony\Component\Notifier\Recipient;


/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
class PostalMailRecipient implements PostalMailRecipientInterface
{
    use PostalMailRecipientTrait;

    public function __construct(PostalAddress $address)
    {
        $this->postalAddress = $address;
    }

    /**
     * @return $this
     */
    public function postalAddress(PostalAddress $postalAddress): static
    {
        $this->postalAddress = $postalAddress;

        return $this;
    }
}