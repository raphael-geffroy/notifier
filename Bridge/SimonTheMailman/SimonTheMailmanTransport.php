<?php

namespace Symfony\Component\Notifier\Bridge\SimonTheMailman;

use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\PostalMailMessage;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;

class SimonTheMailmanTransport extends AbstractTransport
{
    protected const HOST = 'main-bvxea6i-n3he32kjpvp2e.fr-4.platformsh.site';

    public function __construct(
        #[\SensitiveParameter] private readonly string $apiKey,
        ?EventDispatcherInterface $dispatcher = null,
        ?HttpClientInterface $client = null,
    ) {
        parent::__construct($client, $dispatcher);
    }

    public function __toString(): string
    {
        return \sprintf('simonthemailman://%s', $this->getEndpoint());
    }

    protected function doSend(MessageInterface $message): SentMessage
    {
        if(!$message instanceof PostalMailMessage){
            throw new UnsupportedMessageTypeException(__CLASS__, PostalMailMessage::class, $message);
        }

        $response = $this->client->request('POST', 'https://'.$this->getEndpoint().'/api/send', ['auth_basic' => $this->apiKey.':', 'json' => [
            'content' => $message->getSubject(),
        ]])->toArray();

        $messageId = $response['id'] ?? throw new RuntimeException();

        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId($messageId);

        return $sentMessage;
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof PostalMailMessage;
    }
}
