<?php

namespace Symfony\Component\Notifier\Recipient;

/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
interface PostalMailRecipientInterface extends RecipientInterface
{
    public function getPostalAddress(): PostalAddress;
}