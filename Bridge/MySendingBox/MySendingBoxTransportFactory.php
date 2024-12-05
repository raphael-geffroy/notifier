<?php

namespace Symfony\Component\Notifier\Bridge\MySendingBox;

use Symfony\Component\Notifier\Exception\UnsupportedSchemeException;
use Symfony\Component\Notifier\Transport\AbstractTransportFactory;
use Symfony\Component\Notifier\Transport\Dsn;

class MySendingBoxTransportFactory extends AbstractTransportFactory
{
    public final const SCHEME = 'mysendingbox';

    public function create(Dsn $dsn): MySendingBoxTransport
    {
        $scheme = $dsn->getScheme();

        if($scheme !== self::SCHEME){
            throw new UnsupportedSchemeException($dsn, self::SCHEME, $this->getSupportedSchemes());
        }

        return new MySendingBoxTransport(
            $this->getUser($dsn).':',
            $this->dispatcher,
            $this->client
        );
    }

    protected function getSupportedSchemes(): array
    {
        return [self::SCHEME];
    }
}
