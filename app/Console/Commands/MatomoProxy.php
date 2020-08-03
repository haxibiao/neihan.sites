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
    protected $signature = 'matomo:proxy {--num=5} {--port=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '负责tcp代理matomo事件批量http发送到matomo去';

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
            'matomo' => config('matomo.matomo_url'),
        ];
        $tracker = new \MatomoTracker($config['siteId'], $config['matomo']);
        $tracker->setCountry('中国');
        $tracker->setBrowserLanguage('zh-cn');
        $tracker->setTokenAuth(config('matomo.token_auth'));
        $tracker->disableCookieSupport();
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
        $port = $this->option('port') ?? config('matomo.proxy_port');

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
            $this->info("sent events ...");
            $this->events = [];
        } else {
            $this->warn("got events:" . count($this->events));
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
                $this->info("============== sent 用户: $event->user_id 的 events");
            } else {
                $this->warn("用户 $event->user_id 已经累计了 events:" . count($user_events));
            }
        } else {
            $this->info("新用户访问：" . $event->user_id);
            $user_events[]                 = $event;
            $this->events[$event->user_id] = $user_events;
            //FIXME: 定时把所有新用户不够bulk num的每分钟都统一逐个发送出去
        }
    }

    public function sendBulkEvents($events)
    {
        $collection = collect($events);
        $events     = $collection->groupBy('siteId');

        foreach ($events as $siteId => $groupEvents) {
            $this->error("siteId:" . $siteId);
            $tracker = $this->getTracker($siteId);
            try {
                $tracker->enableBulkTracking();
                //循环send
                foreach ($groupEvents as $event) {
                    // $tracker->setCustomVariable(1, '机型', $event->dimension5, 'visit');

                    $tracker->setUserId($event->user_id);
                    $tracker->setIp($event->ip);
                    $tracker->setForceVisitDateTime($event->cdt);

                    $tracker->setCustomVariable(1, '系统', $event->dimension1, 'visit');
                    $tracker->setCustomVariable(2, '来源', $event->dimension2, 'visit');
                    $tracker->setCustomVariable(3, '版本', $event->dimension3, 'visit');
                    $tracker->setCustomVariable(4, '用户', $event->dimension4, 'visit');
                    $tracker->setCustomVariable(5, "服务器", $event->server, "visit");
                    $tracker->setCustomVariable(6, '机型', $event->dimension5, 'visit');

                    // $url = $tracker->getUrlTrackEvent($event->category, $event->action, $event->name, $event->value);
                    //send
                    $tracker->doTrackEvent($event->category, $event->action, $event->name, $event->value);
                }
                //真正的send
                $result = $tracker->doBulkTrack();
                $this->info($result);
            } catch (\Throwable $th) {
                $this->error($th->getMessage());
            }
        }
    }
}
