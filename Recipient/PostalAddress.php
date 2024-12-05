<?php

namespace Symfony\Component\Notifier\Recipient;

/**
 * @author Raphaël Geffroy <raphael@geffroy.dev>
 */
readonly class PostalAddress
{
    public function __construct(
        public string $name,
        public string $streetAddress,
        public string $postalCode,
        public string $city,
        public string $country
    ){
    }
}