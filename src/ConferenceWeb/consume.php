<?php
declare(strict_types=1);

use Bunny\Message;
use Shared\RabbitMQ\Queue;
use function Common\Resilience\retry;

require __DIR__ . '/../../vendor/autoload.php';

$app = new \ConferenceWeb\Application();

retry(3, 1000, function () use ($app) {
    Queue::consume(
        function (Message $message) use ($app) {

            $command = json_decode($message->content, true);

            $redis = new \Predis\Client([
                'host' => 'redis'
            ]);

            $projection = new \stdClass();
            $projection->id = $command['id'];
            $projection->name = $command['name'];
            $projection->start = $command['start'];
            $projection->end = $command['end'];
            $projection->city = $command['city'];


            // store the initial projection
            $redis->hset(
                'conferences',
                $projection->id,
                json_encode($projection)
            );
        },
        'conference_management'
    );
});
