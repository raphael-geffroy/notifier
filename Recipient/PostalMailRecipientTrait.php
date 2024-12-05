<?php

namespace Symfony\Component\Notifier\Recipient;

/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
trait PostalMailRecipientTrait
{
    private PostalAddress $postalAddress;

    public function getPostalAddress(): PostalAddress
    {
        return $this->postalAddress;
    }
}