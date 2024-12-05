<?php

namespace Symfony\Component\Notifier\Bridge\MySendingBox;

use Symfony\Component\Notifier\Exception\TransportException;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Notifier\Exception\UnsupportedMessageTypeException;
use Symfony\Component\Notifier\Message\MessageInterface;
use Symfony\Component\Notifier\Message\PostalMailMessage;
use Symfony\Component\Notifier\Message\SentMessage;
use Symfony\Component\Notifier\Transport\AbstractTransport;

class MySendingBoxTransport extends AbstractTransport
{
    protected const HOST = 'api.mysendingbox.fr';

    public function __construct(
        #[\SensitiveParameter] private readonly string $apiKey,
        ?EventDispatcherInterface $dispatcher = null,
        ?HttpClientInterface $client = null,
    ) {
        parent::__construct($client, $dispatcher);
    }

    /**
     * @param PostalMailMessage $message
     */
    protected function doSend(MessageInterface $message): SentMessage
    {
        if (!$this->supports($message)) {
            throw new UnsupportedMessageTypeException(__CLASS__, PostalMailMessage::class, $message);
        }

        $from = [];
        if($message->getOptions() instanceof MySendingBoxOptions){
            $from = [
                'from' => $message->getOptions()->toArray()['sender']
            ];
        }

        $recipientPostalAddress = $message->getRecipientPostalAddress();
        $to = [
            'company' => $recipientPostalAddress->name,
            'address_line1' => $recipientPostalAddress->streetAddress,
            'address_postalcode' => $recipientPostalAddress->postalCode,
            'address_city' => $recipientPostalAddress->city,
            'address_country' => $recipientPostalAddress->country,
        ];

        $response = $this->client->request('POST', 'https://'.$this->getEndpoint().'/letters', [
            'json' => [
                'to' => $to,
                'color' => 'bw',
                'postage_type' => 'ecopli',
                'source_file' => $message->getSubject(),
                'source_file_type' => 'html'
            ] + $from,
            'auth_basic' => $this->apiKey,
        ]);

        try {
            $statusCode = $response->getStatusCode();
        } catch (TransportExceptionInterface $e) {
            throw new TransportException('Could not reach the remote MySendingBox server.', $response, 0, $e);
        }

        if (200 !== $statusCode) {
            $error = $response->toArray(false);

            throw new TransportException('Unable to send the Postal Mail: '.($error['message'] ?? $response->getContent(false)), $response);
        }

        $success = $response->toArray(false);

        $sentMessage = new SentMessage($message, (string) $this);
        $sentMessage->setMessageId($success['_id']);

        return $sentMessage;
    }

    public function supports(MessageInterface $message): bool
    {
        return $message instanceof PostalMailMessage;
    }

    public function __toString(): string
    {
        return sprintf('%s://%s',MySendingBoxTransportFactory::SCHEME, $this->getEndpoint());
    }
}
