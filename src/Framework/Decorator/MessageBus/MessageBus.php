<?php
declare(strict_types=1);

namespace App\Framework\Decorator\MessageBus;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class MessageBus
{
    public function __construct(private readonly MessageBusInterface $messageBus) {}

    public function dispatch(object $message)
    {
        $envelope = $this->messageBus->dispatch($message);

        $result = $envelope->last(HandledStamp::class);

        return $result instanceof HandledStamp ? $result->getResult() : null;
    }
}
