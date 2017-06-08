<?php
declare(strict_types=1);

use Bunny\Message;
use OrdersAndRegistrations\Application;
use Shared\RabbitMQ\Queue;
use function Common\Resilience\retry;

require __DIR__ . '/../../vendor/autoload.php';

$app = new Application();

retry(3, 1000, function () use ($app) {
    Queue::consume(
        function (Message $message) use ($app) {
            $command = \NaiveSerializer\Serializer::deserialize(\OrdersAndRegistrations\PlaceOrder::class, $message->content);

            $validator = new JsonSchema\Validator;
            $validator->validate($command, json_decode(file_get_contents(__DIR__ . '/placeOrder.json')));

            if ($validator->isValid()) {
                (new Application())->placeOrder($command);
            } else {
                \Common\CommandLine\stdout('ERROR: ' . json_encode($validator->getErrors()));
            }

        },
        'orders_and_registrations'
    );
});
