<?php

namespace Symfony\Component\Notifier\Bridge\SimonTheMailman;

use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;

class SimonTheMailmanTransportFactory extends AbstractTransportFactory
{
    public final const SCHEME = 'simonthemailman';

    public function create(Dsn $dsn): SimonTheMailmanTransport
    {
        $scheme = $dsn->getScheme();

        if ($scheme !== self::SCHEME) {
            throw new UnsupportedSchemeException($dsn, self::SCHEME, $this->getSupportedSchemes());
        }

        $apiKey = $this->getUser($dsn);

        return new SimonTheMailmanTransport(
            $apiKey,
            $this->dispatcher,
            $this->client);
    }

    protected function getSupportedSchemes(): array
    {
        return [self::SCHEME];
    }
}
