<?php

namespace Symfony\Component\Notifier\Message\Letter;

class RawLetter
{
    public function __construct(
        public readonly string $content,
    )
    {
    }
}
