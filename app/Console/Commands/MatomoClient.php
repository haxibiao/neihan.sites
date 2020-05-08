<?php

namespace App\Console\Commands;

use function GuzzleHttp\json_decode;
use function GuzzleHttp\json_encode;
use Illuminate\Console\Command;

class MatomoClient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matomo:client {--siteid=7}　{--test} {--port=9505}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'test send data to matomo proxy';
    protected $tracker = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        if ($this->option('test')) {
            return $this->test_matomo();
        }

        $event = $this->getTestEvent();
        $data = json_encode($event);

        // go(function () use ($data) {
        // //http
        // $http_client = new \Swoole\Coroutine\Http\Client('127.0.0.1', 9501);
        // $http_client->post('/', $data);
        // var_dump($http_client->body);

        // //websocket
        // $http_client->upgrade('/');
        // $http_client->push($data);

        // var_dump($http_client->recv()->data);
        // });

        // go(function () {
        //     // http2
        //     $http2_client = new \Swoole\Coroutine\Http2\Client('localhost', 9501);
        //     $http2_client->connect();
        //     $http2_request = new \Swoole\Http2\Request;
        //     $http2_request->method = 'POST';
        //     $http2_request->data = 'Swoole Http2';
        //     $http2_client->send($http2_request);
        //     $http2_response = $http2_client->recv();
        //     var_dump($http2_response->data);
        // });

        go(function () use ($data) {

            //下面的async 只在cli模式下可以
            // $client = new \swoole_client(SWOOLE_TCP, SWOOLE_SOCK_ASYNC);
            // $client->set([
            //     'open_length_check'     => true,
            //     'package_length_type'   => 'n',
            //     'package_length_offset' => 0,
            //     'package_body_offset'   => 2,
            // ]);
            // $client->connect('127.0.0.1', 9502);
            // $client->send(tcp_pack($data));

            //下面的请求在php-fpm下可以，必须同步
            // $server = "gz003";
            $server = "127.0.0.1";
            $this->info("test tcp to $server");
            try {
                $client = new \swoole_client(SWOOLE_SOCK_TCP); //同步阻塞？？
                $client->connect($server, $this->option('port')) or die("swoole connect failed\n");
                $this->info('connect ...');
                $client->set([
                    'open_length_check' => true,
                    'package_length_type' => 'n',
                    'package_length_offset' => 0,
                    'package_body_offset' => 2,
                ]);
                $client->send(tcp_pack($data));
                var_dump(tcp_unpack($client->recv()));
                $this->info('tcp send success');
            } catch (\Throwable $th) {
                //throw $th;
            }
        });
    }

    public function test_matomo()
    {
        $tracker = $this->getTracker();
        $event = $this->getTestEvent();
        $event_json = json_encode($event);
        $event = json_decode($event_json);

        $tracker->setCustomVariable(1, "Server", gethostname(), "visit");
        $tracker->setUserId($event->user_id);
        $tracker->setIp($event->ip);

        //设备系统
        $tracker->setCustomTrackingParameter('dimension1', $event->dimension1);
        //安装来源
        $tracker->setCustomTrackingParameter('dimension2', $event->dimension2);
        //APP版本
        $tracker->setCustomTrackingParameter('dimension3', $event->dimension3);
        //APP build
        $tracker->setCustomTrackingParameter('dimension4', $event->dimension4);

        //send
        $rs = $tracker->doTrackEvent($event->category, $event->action, $event->name, $event->value);
        $this->info("发送matomo结果:");
        $this->comment($rs);
    }

    public function getTestEvent()
    {
        $event = [];
        $event['siteId'] = $this->option('siteid');
        $event['user_id'] = rand(1, 3);
        $event['ip'] = '127.0.0.1';

        //记录是哪个web服务器来的
        $event['server'] = 'local';

        $event['dimension1'] = 'android';
        $event['dimension2'] = 'xiaomi';
        $event['dimension3'] = '1.4';
        $event['dimension4'] = 'build2';
        $event['dimension5'] = '测试新用户';
        $event['dimension6'] = '测试华为';

        $event['category'] = 'launch';
        $event['action'] = 'visited';
        $event['name'] = '浏览题目';
        $event['value'] = rand(1000, 9999);
        return $event;
    }

    public function getTracker()
    {
        if (!$this->tracker) {
            $config = [
                'siteId' => $this->option('siteid'),
                'matomo' => 'http://matomo.haxibiao.com',
            ];
            $this->tracker = new \MatomoTracker($config['siteId'], $config['matomo']);
        }
        return $this->tracker;
    }
}

function tcp_pack(string $data): string
{
    return pack('n', strlen($data)) . $data;
}
function tcp_unpack(string $data): string
{
    return substr($data, 2, unpack('n', substr($data, 0, 2), 0)[1]);
}
