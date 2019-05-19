<?php

namespace App\Http\REST\v1;

use Core\Http\REST\Controller\ApiBaseController;
use Core\Helpers\Serializer\KeyArraySerializer;

use Bschmitt\Amqp\Facades\Amqp;

use Illuminate\Http\Request;
use Gate;

/**
 * Message resource representation.
 *
 * @Resource("Message", uri="/message")
 */
class MessageController extends ApiBaseController
{

    /**
     * Initialize @var Message
     *
     * @Request Message
     *
     */
    public function __construct()
    {
        parent::__construct();
        
        //$this->middleware('jwt.verify', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of resource.
     *
     * Get a JSON representation of all message.
     *
     * @Get("/message")
     * @Versions({"v1"})
     * @Response(200, body={"id":1,"name":"Message Name"})
     */
    public function rabbitPush(Request $request)
    {
        Amqp::publish($request->routing_key, $request->msg);
        return $this->response->success();
    }

    public function kafka(Request $request)
    {
        // create consumer
        $topicConf = new \RdKafka\TopicConf();
        $topicConf->set('auto.offset.reset', 'largest');

        $conf = new \RdKafka\Conf();
        $conf->set('group.id', 'php-pubsub');
        $conf->set('metadata.broker.list', '127.0.0.1');
        $conf->set('enable.auto.commit', 'false');
        $conf->set('offset.store.method', 'broker');
        $conf->set('socket.blocking.max.ms', 50);
        $conf->setDefaultTopicConf($topicConf);

        $consumer = new \RdKafka\KafkaConsumer($conf);

        // create producer
        $conf = new \RdKafka\Conf();
        $conf->set('socket.blocking.max.ms', 50);
        $conf->set('queue.buffering.max.ms', 20);

        $producer = new \RdKafka\Producer($conf);
        $producer->addBrokers('127.0.0.1');

        $adapter = new \Superbalist\PubSub\Kafka\KafkaPubSubAdapter($producer, $consumer);

        // publish messages
        //$adapter->publish('my_channel', 'HELLO WORLD');
        //$adapter->publish('my_channel', ['hello' => 'world']);
        //$adapter->publish('my_channel', 1);
        //$adapter->publish('my_channel', false);

        return $this->response->success();   
    }

}
