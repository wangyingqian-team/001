<?php
namespace App\Http\Controllers;

use App\Service\RabbitMqManager;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function mq(Request $request)
    {
        $config = [
            'host' => '127.0.0.1',
            'port' => 5672,
            'user' => 'guest',
            'password' => 'guest'
        ];

        $mq = new RabbitMqManager($config);
        $mq->setChannel([
            'channel_id' => 6
        ]);

        $mq->publish();

        die;
    }
}
