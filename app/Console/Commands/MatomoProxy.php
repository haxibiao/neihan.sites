<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MatomoProxy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'matomo:proxy {--num=10} {--port=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'matomo proxy base on swoole, accept client data then send to matomo';

    protected $events = [];

    protected $trackers = [];
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function getTracker($siteId)
    {
        $key      = 'siteID.' . $siteId;
        $trackers = $this->trackers;

        if (isset($trackers[$key])) {
            return $trackers[$key];
        }

        $config = [
            'siteId' => $siteId,
            'matomo' => 'http://matomo.haxibiao.com',
        ];
        $tracker              = new \MatomoTracker($config['siteId'], $config['matomo']);
        $this->trackers[$key] = $tracker;
        return $tracker;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $port = env('MATOMO_PROXY_PORT');
        if ($this->option('port')) {
            $port = $this->option('port');
        }

        $server = new \Swoole\WebSocket\Server('0.0.0.0', $port - 1, SWOOLE_BASE);
        // $server->set(['open_http2_protocol' => true]);

        // websocket on message 回调
        $server->on('message', function (\Swoole\WebSocket\Server $server, \Swoole\WebSocket\Frame $frame) {
            $data = $frame->data;
            try {
                $this->trackEvent($data);
                $server->push($frame->fd, 'websocket server processed ');
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        });

        // tcp 监听
        $tcp_server  = $server->listen('0.0.0.0', $port, SWOOLE_TCP);
        $tcp_options = [
            'open_length_check'     => true,
            'package_length_type'   => 'n',
            'package_length_offset' => 0,
            'package_body_offset'   => 2,
        ];
        $tcp_server->set($tcp_options);
        $tcp_server->on('receive', function (\Swoole\Server $server, int $fd, int $reactor_id, string $data) {
            try {
                $data = tcp_unpack($data);
                $this->trackEvent($data);
                $server->send($fd, tcp_pack('tcp server processed: ' . $data));
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        });
        $server->start();
    }

    public function trackEvent($data)
    {
        //这个日活用户和独立ip数就不对了
        $this->sendEventByNum($data);

        //这样每个用户的请求bulk起来一起发送
        // $this->sendEventByUser($data);
    }

    public function sendEventByNum($data)
    {
        $event          = json_decode($data);
        $this->events[] = $event;
        //当actions累计到n个的时候，把之前的都send
        if (count($this->events) >= $this->option('num')) {
            $this->sendBulkEvents($this->events);
            $this->info("sent " . $this->option('num') . " events to matomo ...");
            $this->events = [];
        } else {
            $this->warn("bulked events:" . count($this->events));
        }
    }

    public function sendEventByUser($data)
    {
        $event = json_decode($data);
        if (array_key_exists($event->user_id, $this->events)) {
            $user_events                   = $this->events[$event->user_id];
            $user_events[]                 = $event;
            $this->events[$event->user_id] = $user_events;
            //当一个用户的actions累计到n个的时候，把之前的都send
            if (count($user_events) >= $this->option('num')) {
                $this->sendBulkEvents($user_events);
                $this->events[$event->user_id] = [];
                $this->info("sent 用户: $event->user_id 的events");
            } else {
                $this->warn("用户 $event->user_id 已经累计了events:" . count($user_events));
            }
        } else {
            $this->info("新用户访问：" . $event->user_id);
            $user_events[]                 = $event;
            $this->events[$event->user_id] = $user_events;
        }
    }

    public function sendBulkEvents($events)
    {
        $collection = collect($events);
        $events     = $collection->groupBy('siteId');

        foreach ($events as $siteId => $groupEvents) {
            $this->info("siteid:" . $siteId);
            $tracker = $this->getTracker($siteId);
            try {
                $tracker->enableBulkTracking();
                //循环send
                foreach ($groupEvents as $event) {
                    // $tracker->setCustomVariable(1, "服务器", $event->server, "visit");
                    // $tracker->setCustomVariable(2, "用户", $event->dimension5, "visit");
                    // $tracker->setCustomVariable(3, "机型", $event->dimension6, "visit");

                    $tracker->setUserId($event->user_id);
                    $tracker->setIp($event->ip);

                    // //设备系统
                    // $tracker->setCustomTrackingParameter('dimension1', $event->dimension1);
                    // //安装来源
                    // $tracker->setCustomTrackingParameter('dimension2', $event->dimension2);
                    // //APP版本
                    // $tracker->setCustomTrackingParameter('dimension3', $event->dimension3);
                    // //APP build
                    // $tracker->setCustomTrackingParameter('dimension4', $event->dimension4);
                    // //新老用户分类
                    // $tracker->setCustomTrackingParameter('dimension5', $event->dimension5);
                    // //用户机型
                    // $tracker->setCustomTrackingParameter('dimension6', $event->dimension6);

                    //send
                    $tracker->doTrackEvent($event->category, $event->action, $event->name, $event->value);
                }
                //真正的send
                $tracker->doBulkTrack();
            } catch (\Throwable $th) {
                \info($events);
                \info($th);
                $this->error($th->getMessage());
            }
        }

    }

    //github上示范的 mixed server
    public function runDemoServer()
    {
        // $server = new \Swoole\WebSocket\Server('0.0.0.0', 9501, SWOOLE_BASE);
        // $server->set(['open_http2_protocol' => true]);

        // // http && http2
        // $server->on('request', function (\Swoole\Http\Request $request, \Swoole\Http\Response $response) {
        //     $data = $request->rawcontent();
        //     try {
        //         $this->trackEvent($data);
        //         $response->end('http2 server processed ');
        //     } catch (\Throwable $th) {
        //         $this->error($th->getMessage());
        //     }
        // });

    }
}
