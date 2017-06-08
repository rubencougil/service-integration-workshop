<?php
declare(strict_types=1);

namespace ConferenceWeb;

use Common\EventSourcing\Aggregate\Repository\EventSourcedAggregateRepository;
use GuzzleHttp\Client;
use NaiveSerializer\Serializer;
use Ramsey\Uuid\Uuid;
use Shared\RabbitMQ\Exchange;
use Shared\StringUtil;

final class Application
{
    public function orderController()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $command = $_POST;
            $command['orderId'] = (string)Uuid::uuid4();

            /*$httpClient = new Client();
            $httpClient->post('http://orders_and_registrations_web:8080/placeOrder', [
                    'body' => Serializer::serialize($command),
                    'headers' => [
                        'Content-Type' => 'application/json'
                    ]
                ]
            );*/

            Exchange::publish('order', $command);

            header('Location: /thankYou?orderId=' . $command['orderId']);
            exit;
        }

        //$conferences = json_decode(file_get_contents('http://conference_management:8080/listConferences'), true);

        $redis = new \Predis\Client([
            'host' => 'redis'
        ]);

        // load all projections
        $conferences = array_map('json_decode', $redis->hgetall('conferences'));

        ?>
        <form action="#" method="post">
            <div>
                <label for="conferenceId">Select a conference:</label>
                <select id="conferenceId" name="conferenceId">
                    <?php foreach ($conferences as $conference): ?>
                        <option value="<?php echo $conference->id; ?>"><?php echo StringUtil::escapeHtml($conference->name); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="numberOfTickets">Number of tickets:</label> <input id="numberOfTickets"
                                                                               name="numberOfTickets" type="number"/>
            </div>
            <button type="submit">Place order</button>
        </form>
        <?php
    }

    public function thankYouController()
    {
        ?>
        Thank you for ordering your ticket(s).
        <script src="https://js.pusher.com/4.0/pusher.min.js"></script>
        <script>
            Pusher.logToConsole = true;

            var pusher = new Pusher('<?php echo getenv('PUSHER_KEY'); ?>', {
                cluster: 'eu',
                encrypted: true
            });

            var channel = pusher.subscribe('my-channel');
            channel.bind('my-event', function(data) {
                alert(data.message);
            });
        </script>
        <?php
    }
}
