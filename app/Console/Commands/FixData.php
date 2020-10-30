<?php

namespace App\Console\Commands;

use App\Gold;
use App\User;
use App\OAuth;
use App\Video;
use App\Visit;
use App\Wallet;
use App\Article;
use App\BadWord;
use App\Profile;
use App\Withdraw;
use App\Collection;
use App\Contribute;
use App\Transaction;
use App\UserRetention;
use Vod\VodUploadClient;
use App\WalletTransaction;
use App\Jobs\ProcessSpider;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;
use Vod\Model\VodUploadRequest;
use Illuminate\Support\Facades\DB;
use TencentCloud\Common\Credential;
use Illuminate\Support\Facades\Storage;
use TencentCloud\Vod\V20180717\VodClient;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Vod\V20180717\Models\PushUrlCacheRequest;
use TencentCloud\Common\Exception\TencentCloudSDKException;

class FixData extends Command
{

    protected $signature = 'fix:data {table}';

    protected $description = 'fix dirty data by table';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        if ($table = $this->argument('table')) {
            return $this->$table();
        }
        return $this->error("必须提供你要修复数据的table");
    }

    public function collectionAsCountPosts(){
        $this->info('fix collections count_posts...');
        Collection::chunk(100, function ($collections) {
            foreach ($collections as $collection) {
                $collection->updateCountPosts();
            }
        });
        $this->info('fix collections count_posts success');
    }

    public function failWithdraws()
    {
        $withdraws = Withdraw::whereIn('id', [5486, 5485])->get();
        foreach ($withdraws as $withdraw) {
            //重新查询锁住该记录更新
            $wallet   = $withdraw->wallet;
            $user     = $wallet->user;

            DB::beginTransaction(); //开启事务
            try {
                //1.更改提现记录
                $withdraw->status = Withdraw::FAILURE_WITHDRAW;
                $withdraw->remark = '提现失败';
                $withdraw->save();

                // 2.退回提现贡献点
                Contribute::create([
                    'user_id' => $user->id,
                    'remark'  => '提现失败返回贡献值',
                    'amount'  => 128,
                    'contributed_id' => $withdraw->id,
                    'contributed_type' => 'withdraws',
                ]);
                //事务提交
                DB::commit();
            } catch (\Exception $ex) {
                Log::error($ex);
                DB::rollback(); //数据回滚
            }
        }
    }

    //马甲号，机器人
    public function robotUser()
    {
        $count  = 0;
        for ($i = 1; $i < 100; $i++) {

            $user = User::firstOrNew([
                'email' => 'author_test' . $i . '@haxibiao.com',
            ]);
            if ($user->id) {
                return;
            }
            $user->account    = $user->email;
            $user->role_id = User::VEST_STATUS;
            $user->phone      = $user->email;
            $user->name       = $this->roundName();
            $user->password   = bcrypt('mm1122');
            $avatar_formatter = 'http://cos.' . env('APP_NAME') . '.com/storage/avatar/avatar-%d.jpg';
            $user->avatar     = sprintf($avatar_formatter, rand(1, 15));
            $user->api_token  = str_random(60);
            $user->save();
            $profile = $user->profile;
            if (empty($profile)) {
                $profile          = new Profile();
                $profile->user_id = $user->id;
            }
            $profile->save();
            $count++;
            $this->info("创建用户成功:" . $user->name);
        }
        $this->info("共创建用户" . $count . '个');
    }


    //随机昵称
    public function roundName()
    { {
            $tou = array(
                '快乐', '冷静', '醉熏', '潇洒', '糊涂', '积极', '冷酷',
                '深情', '粗暴', '温柔', '可爱', '愉快', '义气', '认真', '威武', '帅气',
                '传统', '潇洒', '漂亮', '自然', '专一', '听话', '昏睡', '狂野', '等待',
                '搞怪', '幽默', '魁梧', '活泼', '开心', '高兴', '超帅', '留胡子', '坦率',
                '直率', '轻松', '痴情', '完美', '精明', '无聊', '有魅力', '丰富', '繁荣',
                '饱满', '炙热', '暴躁', '碧蓝', '俊逸', '英勇', '健忘', '故意', '无心',
                '土豪', '朴实', '兴奋', '幸福', '淡定', '不安', '阔达', '孤独', '独特',
                '疯狂', '时尚', '落后', '风趣', '忧伤', '大胆', '爱笑', '矮小', '健康',
                '合适', '玩命', '沉默', '斯文', '香蕉', '苹果', '鲤鱼', '鳗鱼', '任性',
                '细心', '粗心', '大意', '甜甜', '酷酷', '健壮', '英俊', '霸气', '阳光',
                '默默', '大力', '孝顺', '忧虑', '着急', '紧张', '善良', '凶狠', '害怕',
                '重要', '危机', '欢喜', '欣慰', '满意', '跳跃', '诚心', '称心', '如意',
                '怡然', '娇气', '无奈', '无语', '激动', '愤怒', '美好', '感动', '激情',
                '激昂', '震动', '虚拟', '超级', '寒冷', '精明', '明理', '犹豫', '忧郁',
                '寂寞', '奋斗', '勤奋', '现代', '过时', '稳重', '热情', '含蓄', '开放',
                '无辜', '多情', '纯真', '拉长', '热心', '从容', '体贴', '风中', '曾经',
                '追寻', '儒雅', '优雅', '开朗', '外向', '内向', '清爽', '文艺', '长情',
                '平常', '单身', '伶俐', '高大', '懦弱', '柔弱', '爱笑', '乐观', '耍酷',
                '酷炫', '神勇', '年轻', '唠叨', '瘦瘦', '无情', '包容', '顺心', '畅快',
                '舒适', '靓丽', '负责', '背后', '简单', '谦让', '彩色', '缥缈', '欢呼',
                '生动', '复杂', '慈祥', '仁爱', '魔幻', '虚幻', '淡然', '受伤', '雪白',
                '高高', '糟糕', '顺利', '闪闪', '羞涩', '缓慢', '迅速', '优秀', '聪明', '含糊',
                '俏皮', '淡淡', '坚强', '平淡', '欣喜', '能干', '灵巧', '友好', '机智', '机灵',
                '正直', '谨慎', '俭朴', '殷勤', '虚心', '辛勤', '自觉', '无私', '无限', '踏实',
                '老实', '现实', '可靠', '务实', '拼搏', '个性', '粗犷', '活力', '成就', '勤劳',
                '单纯', '落寞', '朴素', '悲凉', '忧心', '洁净', '清秀', '自由', '小巧', '单薄',
                '贪玩', '刻苦', '干净', '壮观', '和谐', '文静', '调皮', '害羞', '安详', '自信',
                '端庄', '坚定', '美满', '舒心', '温暖', '专注', '勤恳', '美丽', '腼腆', '优美',
                '甜美', '甜蜜', '整齐', '动人', '典雅', '尊敬', '舒服', '妩媚', '秀丽', '喜悦',
                '甜美', '彪壮', '强健', '大方', '俊秀', '聪慧', '迷人', '陶醉', '悦耳', '动听',
                '明亮', '结实', '魁梧', '标致', '清脆', '敏感', '光亮', '大气', '老迟到', '知性',
                '冷傲', '呆萌', '野性', '隐形', '笑点低', '微笑', '笨笨', '难过', '沉静', '火星上',
                '失眠', '安静', '纯情', '要减肥', '迷路', '烂漫', '哭泣', '贤惠', '苗条', '温婉',
                '发嗲', '会撒娇', '贪玩', '执着', '眯眯眼', '花痴', '想人陪', '眼睛大', '高贵',
                '傲娇', '心灵美', '爱撒娇', '细腻', '天真', '怕黑', '感性', '飘逸', '怕孤独', '忐忑',
                '高挑', '傻傻', '冷艳', '爱听歌', '还单身', '怕孤单', '懵懂'
            );
            $do = array(
                "的", "爱", "", "与", "给", "扯", "和", "用", "方", "打", "就",
                "迎", "向", "踢", "笑", "闻", "有", "等于", "保卫", "演变"
            );
            $wei = array(
                '嚓茶', '凉面', '便当', '毛豆', '花生', '可乐', '灯泡',
                '哈密瓜', '野狼', '背包', '眼神', '缘分', '雪碧', '人生', '牛排', '蚂蚁',
                '飞鸟', '灰狼', '斑马', '汉堡', '悟空', '巨人', '绿茶', '自行车', '保温杯',
                '大碗', '墨镜', '魔镜', '煎饼', '月饼', '月亮', '星星', '芝麻', '啤酒', '玫瑰',
                '大叔', '小伙', '哈密瓜，数据线', '太阳', '树叶', '芹菜', '黄蜂', '蜜粉', '蜜蜂',
                '信封', '西装', '外套', '裙子', '大象', '猫咪', '母鸡', '路灯', '蓝天', '白云',
                '星月', '彩虹', '微笑', '摩托', '板栗', '高山', '大地', '大树', '电灯胆', '砖头',
                '楼房', '水池', '鸡翅', '蜻蜓', '红牛', '咖啡', '机器猫', '枕头', '大船', '诺言',
                '钢笔', '刺猬', '天空', '飞机', '大炮', '冬天', '洋葱', '春天', '夏天', '秋天',
                '冬日', '航空', '毛衣', '豌豆', '黑米', '玉米', '眼睛', '老鼠', '白羊', '帅哥',
                '美女', '季节', '鲜花', '服饰', '裙子', '白开水', '秀发', '大山', '火车', '汽车',
                '歌曲', '舞蹈', '老师', '导师', '方盒', '大米', '麦片', '水杯', '水壶', '手套', '鞋子',
                '自行车', '鼠标', '手机', '电脑', '书本', '奇迹', '身影', '香烟', '夕阳', '台灯', '宝贝',
                '未来', '皮带', '钥匙', '心锁', '故事', '花瓣', '滑板', '画笔', '画板', '学姐', '店员',
                '电源', '饼干', '宝马', '过客', '大白', '时光', '石头', '钻石', '河马', '犀牛', '西牛',
                '绿草', '抽屉', '柜子', '往事', '寒风', '路人', '橘子', '耳机', '鸵鸟', '朋友', '苗条',
                '铅笔', '钢笔', '硬币', '热狗', '大侠', '御姐', '萝莉', '毛巾', '期待', '盼望', '白昼',
                '黑夜', '大门', '黑裤', '钢铁侠', '哑铃', '板凳', '枫叶', '荷花', '乌龟', '仙人掌', '衬衫',
                '大神', '草丛', '早晨', '心情', '茉莉', '流沙', '蜗牛', '战斗机', '冥王星', '猎豹', '棒球',
                '篮球', '乐曲', '电话', '网络', '世界', '中心', '鱼', '鸡', '狗', '老虎', '鸭子', '雨',
                '羽毛', '翅膀', '外套', '火', '丝袜', '书包', '钢笔', '冷风', '八宝粥', '烤鸡', '大雁',
                '音响', '招牌', '胡萝卜', '冰棍', '帽子', '菠萝', '蛋挞', '香水', '泥猴桃', '吐司', '溪流',
                '黄豆', '樱桃', '小鸽子', '小蝴蝶', '爆米花', '花卷', '小鸭子', '小海豚', '日记本', '小熊猫',
                '小懒猪', '小懒虫', '荔枝', '镜子', '曲奇', '金针菇', '小松鼠', '小虾米', '酒窝', '紫菜',
                '金鱼', '柚子', '果汁', '百褶裙', '项链', '帆布鞋', '火龙果', '奇异果', '煎蛋', '唇彩',
                '小土豆', '高跟鞋', '戒指', '雪糕', '睫毛', '铃铛', '手链', '香氛', '红酒', '月光',
                '酸奶', '银耳汤', '咖啡豆', '小蜜蜂', '小蚂蚁', '蜡烛', '棉花糖', '向日葵', '水蜜桃',
                '小蝴蝶', '小刺猬', '小丸子', '指甲油', '康乃馨', '糖豆', '薯片', '口红', '超短裙',
                '乌冬面', '冰淇淋', '棒棒糖', '长颈鹿', '豆芽', '发箍', '发卡', '发夹', '发带', '铃铛',
                '小馒头', '小笼包', '小甜瓜', '冬瓜', '香菇', '小兔子', '含羞草', '短靴', '睫毛膏', '小蘑菇',
                '跳跳糖', '小白菜', '草莓', '柠檬', '月饼', '百合', '纸鹤', '小天鹅', '云朵', '芒果', '面包',
                '海燕', '小猫咪', '龙猫', '唇膏', '鞋垫', '羊', '黑猫', '白猫', '万宝路', '金毛', '山水',
                '音响', '尊云', '西安'
            );

            $tou_num = rand(0, 331);
            $do_num = rand(0, 19);
            $wei_num = rand(0, 327);
            $type = rand(0, 1);
            if ($type == 0) {
                $username = $tou[$tou_num] . $do[$do_num] . $wei[$wei_num];
            } else {
                $username = $wei[$wei_num] . $tou[$tou_num];
            }
            return $username;
        }
    }

    /**
     * 修复抖音抓取视频的描述信息
     */
    public function fixDescription()
    {
        Article::whereNotNull('source_url')->where('video_id', '<>', 1)->chunk(1000, function ($articles) {
            foreach ($articles as $article) {
                $video = $article->video;
                if (!$video) {
                    continue;
                }
                if (!$video->json) {
                    continue;
                }
                $json = json_decode($video->json, true);
                $desc = Arr::get($json, 'metaInfo.item_list.0.desc', null);
                if (is_null($desc)) {
                    continue;
                }
                $desc = str_replace(['#在抖音，记录美好生活#', '@抖音小助手', '抖音', 'dou', 'Dou', 'DOU', '抖音助手'], '', $desc);
                $this->info($desc);
                $article->description = $desc;
                $article->title       = $desc;
                $article->body        = $desc;
                $article->save(['timestamps' => false]);
            }
        });
    }

    public function contribute()
    {
        Contribute::chunk(
            100,
            function ($contributes) {
                foreach ($contributes as $contribute) {
                    $contributed = $contribute->contributed_type;

                    if ($contributed == "articles") {
                        $contribute->remark = '发布视频奖励';
                    }
                    if ($contributed == "AD") {
                        $contribute->remark = '观看广告奖励';
                    }

                    if ($contributed == "AD_VIDEO") {
                        $contribute->remark = '观看激励视频奖励';
                    }
                    if ($contributed == "issues") {
                        $contribute->remark = '悬赏奖励';
                    }
                    if ($contributed == "usertasks") {
                        $contribute->remark = '任务奖励';
                    }

                    $contribute->timestamps = false;
                    $this->info('id' . $contribute->id . 'remark' . $contribute->remark);
                    $contribute->save();
                }
            }
        );
    }

    public function userActivities()
    {
        //最近90天的日活统计信息(不包含今天)
        $endOfDay = Carbon::yesterday();
        $cacheKey = 'nova_user_activity_num_of_%s';
        for ($i = 0; $i <= 90; $i++) {
            $count = Visit::whereDate('created_at', $endOfDay)->distinct()->count('user_id');
            $key   = sprintf($cacheKey, $endOfDay->toDateString());
            $this->info($endOfDay->toDateString());
            $this->info($count);
            cache()->store('database')->forever($key, $count);
            $endOfDay = $endOfDay->subDay(1);
        }
    }

    public function articles()
    {
        $count = 0;

        Article::where('source_url', 'like', 'https://v.douyin.com%')
            ->where('submit', Article::SUBMITTED_SUBMIT)->chunk(1000, function ($posts) use (&$count) {
                foreach ($posts as $post) {
                    $videoId = $post->video_id;
                    //截图正常的视频则跳过
                    if (Str::contains($post->cover_path, $videoId)) {
                        continue;
                    }
                    $video = $post->video;
                    $this->info($post->id);
                    if ($video) {
                        $count++;
                        $post->cover_path = $video->cover;
                        $post->save(['timestamps' => false]);
                    }
                }
            });
    }

    //修复黑屏视频
    public function articles2()
    {
        $count = 0;
        Article::where('source_url', 'like', 'https://v.douyin.com%')
            ->where('video_id', 1)
            ->chunk(1000, function ($posts) use (&$count) {
                foreach ($posts as $post) {
                    $shareMsg = $post->description . ' ' . $post->source_url;
                    ProcessSpider::dispatch($post, $shareMsg)->onQueue('spider');
                }
            });
    }

    public function articles3()
    {
        $count = 0;
        Article::where('description', 'like', '%mp4%')
            ->chunk(1000, function ($posts) use (&$count) {
                foreach ($posts as $post) {
                    //软删除
                    $post->delete();
                    $this->info($post->id);
                    $count++;
                }
            });
        $this->info($count);
    }

    public function fixContributeRemark()
    {
        $contributes = Contribute::all();
        foreach ($contributes as $contribute) {

            $contributed = $contribute->contributed_type;
            if ($contributed == "AD_VIDEO") {
                $contribute->remark = '观看激励视频奖励';
            }
            $contribute->timestamps = false;
            echo 'id' . $contribute->id;

            $contribute->save();
        }
    }

    public function retentions()
    {
        User::with('visits')->chunk(100, function ($users) {
            foreach ($users as $user) {

                $registed_at = $user->created_at;
                if (!$registed_at) {
                    continue;
                }
                //次日留存
                $next_day_retention_at = $registed_at->addDay(1);
                $is_next_day_retention = $user->visits()->whereDate('created_at', $next_day_retention_at)->count() > 0;
                if ($is_next_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->next_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //3日留存
                $third_day_retention_at = $registed_at->addDay(3);
                $is_third_day_retention = $user->visits()->whereDate('created_at', $third_day_retention_at)->count() > 0;
                if ($is_third_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->third_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //5日留存
                $fifth_day_retention_at = $registed_at->addDay(5);
                $is_fifth_day_retention = $user->visits()->whereDate('created_at', $fifth_day_retention_at)->count() > 0;
                if ($is_fifth_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->fifth_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //7日留存
                $sixth_day_retention_at = $registed_at->addDay(7);
                $is_sixth_day_retention = $user->visits()->whereDate('created_at', $sixth_day_retention_at)->count() > 0;
                if ($is_sixth_day_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->sixth_day_retention_at = $user->created_at;
                    $retention->save();
                }

                $registed_at = $user->created_at;
                //30日留存
                $month_retention_at = $registed_at->addDay(7);
                $is_month_retention = $user->visits()->whereDate('created_at', $month_retention_at)->count() > 0;
                if ($is_month_retention) {
                    $retention = UserRetention::firstOrNew([
                        'user_id' => $user->id,
                    ]);
                    $retention->month_retention_at = $user->created_at;
                    $retention->save();
                }
            }
        });
    }

    public function fixUnUploadtoVodVideos()
    {
        Article::with('video')->whereNotNull('source_url')
            ->whereNotNull('video_id')->chunk(100, function ($articles) {
                foreach ($articles as $article) {
                    $video = $article->video;
                    if (!$video || $video->disk != 'local') {
                        continue;
                    }
                    $this->info($article->id);
                    $json = json_decode($video->json, true);

                    $shareMsg = Arr::get($json, 'metaInfo.item_list.0.desc') . ' ' . $article->source_url;

                    //队列开始处理爬虫视频
                    ProcessSpider::dispatch($article, $shareMsg)->onQueue('spider');
                }
            });
    }

    /**
     * 1.VOD视频PUSH到线上数据库
     */
    public function uploadVod()
    {
        $vodClassIds = [
            'dianmoge'     => 621049,
            'dongdianyao'  => 621585,
            'ainicheng'    => 621586,
            'dongdianfa'   => 621587,
            'youjianqi'    => 621588,
            'dongmeiwei'   => 621589,
            'dongdaima'    => 621590,
            'dongqizhi'    => 621591,
            'tongjiuxiu'   => 621592,
            'dongdianmei'  => 621593,
            'haxibiao'     => null,
            'qunyige'      => 621594,
            'dongdianyi'   => 621595,
            'caohan'       => 621596,
            'gba-port'     => 621597,
            'gba-life'     => 621598,
            'dongwaiyu'    => 621599,
            'dongyundong'  => 621600,
            'dongwuli'     => 621601,
            'jucheshe'     => 621602,
            'dongshouji'   => 621607,
            'dongdiancai'  => 621608,
            'dongshengyin' => 621609,
            'diudie'       => 621610,
            'dongdezhuan'  => 621611,
            'dongmiaomu'   => 619195,
        ];
        Article::whereNotNull('video_id')->where('status', 1)->where('submit', 1)->chunk(
            100,
            function ($articles) use ($vodClassIds) {
                foreach ($articles as $article) {
                    $video = $article->video;
                    if (!$video) {
                        continue;
                    }
                    //不处理已经上传到VOD的视频
                    if (Str::contains($video->path, 'vod2')) {
                        continue;
                    }
                    $client = new VodUploadClient(
                        "AKIDKZeYH6uMdqyxkxKyhFuQ0W5ThliVtWlq",
                        "61nNlyzqWxLbgaIpBMPM8lCWfeSAkEaq"
                    );
                    $client->setLogPath(storage_path('/logs/vod_upload.log'));
                    $req = new VodUploadRequest();
                    if (\str_contains($video->url, 'cos')) {
                        Storage::put($video->path, @file_get_contents($video->url));
                    } else {
                        $result = @file_get_contents(\Storage::disk('cosv5')->url($video->path));
                        if (!$result) {
                            $this->error($video->id);
                            continue;
                        }
                        Storage::put($video->path, $result);
                    }

                    try {
                        Storage::put(
                            $article->cover_path,
                            @file_get_contents(\Storage::disk('cosv5')->url($article->cover_path))
                        );
                        $req->MediaFilePath = storage_path('app/public/' . $video->path);
                        //!!! 注意替换这个分类ID
                        $req->ClassId       = $vodClassIds[env('APP_NAME')];
                        $req->CoverFilePath = storage_path('app/public/' . $article->cover_path);
                        $req->CoverType     = 'jpg';
                        $rsp                = $client->upload("ap-guangzhou", $req);
                        echo "MediaUrl -> " . $rsp->MediaUrl . "\n";
                        $this->info($video->id);
                        $video->disk         = 'vod';
                        $video->qcvod_fileid = $rsp->FileId;
                        $video->path         = $rsp->MediaUrl;
                        $video->save(['timestamps' => false]);
                    } catch (\Exception $e) {
                        // 处理上传异常
                        $this->error($video->id);
                        echo $e;
                        continue;
                    }
                }
            }
        );
    }

    /**
     * 2.VOD视频预热
     */
    public function pushUrlCache()
    {
        Video::where('disk', 'vod')->chunk(20, function ($videos) {
            $videoUrl = [];
            foreach ($videos as $video) {
                $videoUrl[] = $video->url;
            }
            if ($videoUrl) {
                try {
                    $cred = new Credential(
                        "AKIDKZeYH6uMdqyxkxKyhFuQ0W5ThliVtWlq",
                        "61nNlyzqWxLbgaIpBMPM8lCWfeSAkEaq"
                    );
                    $httpProfile = new HttpProfile();
                    $httpProfile->setEndpoint("vod.tencentcloudapi.com");

                    $clientProfile = new ClientProfile();
                    $clientProfile->setHttpProfile($httpProfile);
                    $client = new VodClient($cred, "ap-guangzhou", $clientProfile);

                    $req = new PushUrlCacheRequest();

                    $params = '{"Urls":["' . implode('","', $videoUrl) . '"]}';
                    $req->fromJsonString($params);

                    $resp = $client->PushUrlCache($req);

                    print_r($resp->toJsonString());
                } catch (TencentCloudSDKException $e) {
                    echo $e;
                }
                sleep(1);
            }
        });
    }

    public function importVideo()
    {
        $videos = DB::connection('dianmoge_vod')->table('videos')->get();
        foreach ($videos as $video) {
            if (Str::contains($video->path, '1254284941')) {
                $oldVideo               = Video::find($video->id);
                $oldVideo->path         = $video->path;
                $oldVideo->qcvod_fileid = $video->qcvod_fileid;
                $oldVideo->save(['timestamps' => false]);
                $this->info($video->id);
            }
        }
    }

    /**
     * 增加默认视频
     */
    public function video()
    {

        //保存黑屏视频
        if (Storage::cloud()->exists('video/1.mp4')) {
            Storage::cloud()->move('video/1.mp4', 'video/1_old.mp4');
        }
        Storage::cloud()->put('video/1.mp4', @file_get_contents('http://cos.dianmoge.com/video/1.mp4'));
        Storage::cloud()->put(
            'video/1.mp4.0_0.p0.jpg',
            @file_get_contents('http://cos.dianmoge.com/video/1.mp4.0_0.p0.jpg ')
        );

        //下架Video_id的Article
        $video = Video::find(1);
        //黑屏视频与图片处理 video id处理
        if ($video) {
            $article         = $video->article;
            $article->status = -1;
            $article->sunmit = -1;
            $article->save();

            $video->user_id  = 1;
            $video->title    = '黑屏视频';
            $video->path     = 'video/1.mp4';
            $video->cover    = 'video/1.mp4.0_0.p0.jpg';
            $video->duration = 15;
            $video->hash     = '4073b0265f5d794b5fd5653e2cf18dae';
            $video->setJsonData('covers', ["video/1.mp4.0_0.p0.jpg"]);
            $video->setJsonData('cover', 'video/1.mp4.0_0.p0.jpg');
            $video->save();
        } else {
            $video           = new Video();
            $video->user_id  = 1;
            $video->title    = '黑屏视频';
            $video->path     = 'video/1.mp4';
            $video->cover    = 'video/12.98.jpg';
            $video->duration = 15;
            $video->hash     = '4073b0265f5d794b5fd5653e2cf18dae';
            $video->setJsonData('covers', ["video/1.mp4.0_0.p0.jpg"]);
            $video->setJsonData('cover', 'video/1.mp4.0_0.p0.jpg');
            $video->setJsonData('width', 576);
            $video->setJsonData('height', 1024);
            $video->save();
        }
    }

    public function BadWord()
    {
        $file     = dirname(__FILE__) . '/../../Helpers/BadWord/' . 'bad-word.txt';
        $text     = file_get_contents($file);
        $badWords = explode("\n", $text);
        $badWords = array_map(function ($text) {
            $text = base64_decode($text);
            $text = str_replace(["\n", "\r", "\n\r", '"', ';'], '', $text);
            return $text;
        }, $badWords);

        foreach ($badWords as $badWord) {
            $badword = BadWord::firstOrCreate([
                'word' => $badWord,
            ]);

            $this->info('id' . $badword->id . '的词' . $badword->word);
        }
    }

    /**
     * 修复转码视频
     */
    public function videos()
    {
        $count      = 0;
        $dispatcher = Video::getEventDispatcher();
        Video::unsetEventDispatcher();
        Video::chunk(100, function ($videos) {
            foreach ($videos as $video) {
                $path = $video->path;
                $hd   = $path . '.f30.mp4';
                $sd   = $path . '.f20.mp4';
                $ld   = $path . '.f10.mp4';
                if (Storage::cloud()->exists($hd)) {
                    $video->setJsonData('transcode_hd_mp4', $hd);
                    $video->path = $hd;
                    $video->save();
                }
                if (Storage::cloud()->exists($sd)) {
                    $video->setJsonData('transcode_sd_mp4', $sd);
                }
                if (Storage::cloud()->exists($ld)) {
                    $video->setJsonData('transcode_ld_mp4', $ld);
                }
            }
        });
        Video::setEventDispatcher($dispatcher);
        $this->info($count);
        $this->info('fix videos finished...');
    }

    public function withdraws()
    {
        \DB::beginTransaction();
        try {
            //提现成功：[953,976];
            $withdraws = Withdraw::whereBetween('id', [1012, 1030])->get();
            foreach ($withdraws as $withdraw) {
                $wallet = $withdraw->wallet;
                $amount = $withdraw->amount;

                $amount                 = -1 * $amount;
                $balance                = $wallet->balance + $amount;
                $transaction            = new Transaction();
                $transaction->wallet_id = $wallet->id;
                $transaction->type      = '提现';
                $transaction->remark    = '延迟扣款';
                $transaction->status    = '已支付';
                $transaction->balance   = $balance;
                $transaction->amount    = $amount;
                $transaction->save();

                $withdraw->status         = Withdraw::SUCCESS_WITHDRAW;
                $withdraw->remark         = '提现成功';
                $withdraw->transaction_id = $transaction->id;
                $withdraw->to_platform    = 'Alipay';
                $withdraw->save();
            }

            \DB::commit();
        } catch (\Exception $e) {
            dd($e);
            \DB::rollBack();
        }
    }

    public function fixArticle()
    {
        $articles = Article::where('cover_path', 'video/12.98.jpg')->get();
        foreach ($articles as $article) {
            $cover               = sprintf('video/%s.mp4.0_0.p0.jpg', $article->video_id);
            $article->cover_path = $cover;
            $article->save(['timestamps' => false]);
            $video = $article->video;
            $video->setJsonData('covers', [$cover]);
            $video->setJsonData('cover', $cover);
        }
    }

    //合并WalletTransaction与Transaction表
    public function mergeWalletTransactionToTransaction()
    {

        $this->info('merge WalletTransaction To Transaction start...');

        //合并失败的WalletTransaction IDs
        $failIds = [];

        WalletTransaction::orderBy('id', 'asc')->chunk(100, function ($walletTransactions) use (&$failIds) {
            foreach ($walletTransactions as $walletTransaction) {
                \DB::beginTransaction();
                $id = $walletTransaction->id;
                try {
                    //TODO 幂等操作

                    $newTran             = new Transaction();
                    $newTran->wallet_id  = $walletTransaction->wallet_id;
                    $newTran->amount     = $walletTransaction->amount;
                    $newTran->balance    = $walletTransaction->balance;
                    $newTran->remark     = $walletTransaction->remark;
                    $newTran->created_at = $walletTransaction->created_at;
                    $newTran->updated_at = $walletTransaction->updated_at;
                    $newTran->type       = '兑换';
                    if ($walletTransaction->remark == '智慧点兑换') {
                        $newTran->status = '已兑换';
                    } else {
                        $newTran->status = '已支付';
                    }
                    $newTran->save(['timestamps' => false]);

                    if ($walletTransaction->remark != '智慧点兑换') {
                        //兼容withdraw中的transaction_id变动
                        $withdraw                 = Withdraw::where('transaction_id', $id)->firstOrFail();
                        $withdraw->transaction_id = $newTran->id;
                        $withdraw->save(['timestamps' => false]);
                    }

                    \DB::commit();
                } catch (\Exception $e) {
                    $failIds[] = $id;
                    \DB::rollBack();
                }
            }
        });
        $this->info('合并失败数:' . count($failIds));
        $this->info('merge WalletTransaction To Transaction finished...');
    }

    public function golds()
    {
        Gold::chunk(100, function ($golds) {
            foreach ($golds as $gold) {
                $user            = $gold->user;
                $goldWallet      = $user->goldWallet;
                $gold->wallet_id = $goldWallet->id;
                $gold->save(['timestamps' => false]);
            }
        });
        $this->info('fix golds finished...');
    }

    public function videoCover()
    {
        $articles = Article::whereBetween('id', [4337, 4360])->get();
        foreach ($articles as $article) {
            if (!$article->video_id) {
                continue;
            }
            $article->image_url = 'http://cos.dianmoge.com/video/' . $article->video_id . '.mp4.0_0.p0.jpg';
            $article->save(['timestamps' => false]);
        }
    }

    public function articleVieoCover()
    {
        $this->info('fix videos ...');
        // $videos = Video::where('status', '!=', -1);
        $articles = Article::where('status', '!=', -1);

        $articles->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $video = $article->video;

                if (!empty($video) && $video->status != -1) {
                    $cover = $video->cover;
                    if (\str_contains($cover, ['vod2.'])) {
                        $covers = $video->JsonData('covers');

                        $cosCovers = [];
                        $cosCover  = [];

                        if (empty($covers)) {
                            $video->setJsonData('covers', [$cover]);
                            $covers = $video->JsonData('covers');
                        }

                        foreach ($covers as $index => $cover) {
                            //有些地址会超时,此函数有问题
                            $url_status     = @get_headers($cover, 1);
                            $http_ok_status = false;
                            //地址获取是否有问题
                            if ($url_status) {
                                //判断是否是200状态吗
                                if (str_contains($url_status[0], "200")) {
                                    $http_ok_status = true;
                                } else {
                                    if (str_contains($url_status[0], "301")) {
                                        //判断是否是重定向
                                        if (str_contains($url_status[1], "200")) {
                                            $http_ok_status = true;
                                        } else {
                                            $this->error("错误状态" . $url_status[0]);
                                        }
                                    }
                                }
                            }

                            if ($http_ok_status && \str_contains($cover, ['vod2.'])) {
                                $sub_cover_str          = $video->id . '.' . $index;
                                $localImagePathTemplate = storage_path('app/public/video/' . '%s.jpg');
                                $localCoverPath         = sprintf($localImagePathTemplate, $sub_cover_str);

                                // \Storage::disk('public')->put($localCoverPath, file_get_contents($cover));
                                // $local_cover = \Storage::disk('public')->get($localCoverPath);
                                // $cosDisk     = \Storage::cloud();

                                $cos_storage_cover = '/storage/video/' . $sub_cover_str . '.jpg';
                                // $cosDisk->put($cos_storage_cover, $local_cover);
                                $cosCover[] = $cos_storage_cover;
                                // $cosCovers[] = \Storage::cloud()->url($cos_storage_cover);
                            }
                        }

                        $video->cover = (!empty($cosCover)) ? $cosCover[0] : null;
                        $video->setJsonData('covers', $cosCovers);
                        $video->timestamps = false;
                        $video->save();
                        $article = $video->article;
                        if (!empty($article)) {
                            if (empty($video->cover)) {
                                $article->status = -1;
                            } else {
                                $article->cover_path = $video->cover;
                            }

                            $article->timestamps = false;
                            $article->save();
                            $this->info($video->id . '视频的封面' . $video->cover . '文章' . $article->id . '的封面' . $article->cover_path);
                        }
                    }
                } else {
                    $article->status     = -1;
                    $article->timestamps = false;
                    $article->save();
                    $this->info('文章' . $article->id . '的状态' . $article->status);
                }
            }
        });
    }

    public function categories()
    {
        // Article

        $articles = Article::whereIn('type', ['video', 'post'])->whereNotNull('category_id');

        $articles->chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $categories = $article->hasCategories()->pluck('category_id')->toArray();
                $category   = $article->category;
                if (!in_array($category->id, $categories)) {
                    array_push($categories, $category->id);
                    $category_ids = array_unique($categories);

                    $result = [];
                    foreach ($category_ids as $categoryId) {
                        $result[intval($categoryId)] = [
                            'submit' => '已收录',
                        ];
                    }
                    $article->hasCategories()->sync($result);
                }

                foreach ($article->categories as $category) {
                    $this->info($article->id . '号文章的' . $category->id . 'id以及name' . $category->name);
                }
            }
        });
    }

    public function follows()
    {
        $builder = User::where('status', User::STATUS_ONLINE);
        $builder->chunkById(100, function ($users) {
            foreach ($users as $user) {
                $this->info($user->id . '修复前，粉丝数：' . $user->count_follows);
                $this->info($user->id . '修复前，关注数：' . $user->count_followings);

                $user->count_followings = $user->followings()->where('followed_type', 'users')->count();
                $user->count_follows    = $user->follows()->where('followed_type', 'users')->count();
                $user->save();

                $this->info('修复后，粉丝数：' . $user->count_follows);
                $this->info('修复后，关注数：' . $user->count_followings);
            }
        });
    }

    public function content($article)
    {
        $body = $article->body;
        $preg = "/<img.*?src=[\"|\'](.*?)[\"|\'].*?>/";
        preg_match_all($preg, $body, $matchs);
        $image_tag = $matchs[0][0];
        $image_url = $matchs[1][0];
        $preg      = "/.*?thumbnail_(\d+)/";
        preg_match_all($preg, $image_url, $matchs);
        $video_id = $matchs[1][0];
        if (!empty($image_url)) {
            $article->body      = str_replace($image_tag, '', $body);
            $article->status    = -1;
            $article_categories = $article->categories()->get();
            $newArticle         = Article::where('video_id', $video_id)->first();
            if ($newArticle) {
                foreach ($article_categories as $category) {
                    $newArticle->categories()->attach([
                        $category->id => [
                            'created_at' => $category->pivot->created_at,
                            'updated_at' => $category->pivot->updated_at,
                            'submit'     => $category->pivot->submit,
                        ],
                    ]);
                }
            }
            $article->save();
            $this->info('Article Id:' . $article->id . ' fix success');
        }
    }

    public function collections()
    {
        $this->info('fix collections ...');
        Collection::chunk(100, function ($collections) {
            foreach ($collections as $conllection) {
                $conllection_id = $conllection->id;
                if (count($conllection->articles()->pluck('article_id')) > 0) {
                    $article_id_arr = $conllection->articles()->pluck('article_id');
                    foreach ($article_id_arr as $article_id) {
                        $article                = Article::find($article_id);
                        $article->collection_id = $conllection_id;
                        $article->save();
                        $conllection->count_words += $article->count_words;
                        $this->info('Artcile:' . $article_id . ' corresponding collections:' . $conllection_id);
                    }
                    $conllection->count = count($article_id_arr);
                    $conllection->save();
                }
                //
            }
        });
        $this->info('fix collections success');
    }

    public function article_comments()
    {
        //修复Article评论数据
        $this->info('fix article comments...');
        Comment::whereNull('comment_id', 'and', true)->chunk(100, function ($comments) {
            foreach ($comments as $comment) {
                if (empty(Comment::find($comment->comment_id))) {
                    $article_id = $comment->commentable_id;
                    $comment->delete();
                    $this->info('文章： https://l.ainicheng.com/article/' . $article_id);
                }
            }
        });
        $this->info('fix articles count_comments...');
        //修复Article评论统计数据
        Article::chunk(100, function ($articles) {
            foreach ($articles as $article) {
                $article->count_replies  = $article->comments()->count();
                $article->count_comments = $article->comments()->max('lou');
                $article->save();
            }
        });
        $this->info('fix success');
    }

    public function visits()
    {
        //获取视频的最大ID
        $max_video_id = Video::max('id');

        $index         = 0;
        $success_index = 0;
        $failed_index  = 0;

        $visits = Visit::where('visited_type', 'videos')->where('visited_id', '>', $max_video_id)->get();
        foreach ($visits as $visit) {
            $index++;
            try {
                $video_id = Article::findOrFail($visit->visited_id)->video_id;
            } catch (\Exception $e) {
                //如果这条记录不存在的话 删除掉这条浏览记录
                $visit_id = $visit->id;
                $visit->delete();
                $failed_index++;
                $this->error('visits ID:' . $visit_id . ' delete');
                continue;
            }

            $visit->visited_id = $video_id;
            $visit->timestamps = false;
            $visit->save();
            $success_index++;

            $this->info(env('APP_DOMAIN') . ' visits Id:' . $visit->id . ' fix success');
        }

        $this->info('总共fix数据:' . $index . '条,成功:' . $success_index . ',失败:' . $failed_index);
    }

    public function actions()
    {
        $this->info('fix action status ....');
        $fix_count    = 0;
        $delete_count = 0;
        //给actions表中 增加status 属性 fix数据
        \App\Action::chunk(100, function ($actions) use (&$fix_count, &$delete_count) {
            foreach ($actions as $action) {
                //获取对应的多态关联关系
                $actionable = $action->actionable;
                $action_id  = $action->id;
                //存在的数据fix 不存在的数据直接删除
                if ($actionable) {
                    switch ($action->actionable_type) {
                        case 'articles':
                            if ($actionable->status == 1) {
                                $action->status = 1;
                            }
                            break;

                        case 'comments':
                            if ($actionable->commentable_type == 'articles') {
                                if ($actionable->article->status == 1) {
                                    $action->status = 1;
                                }
                            }
                            break;

                        case 'favorites':
                            if ($actionable->faved_type == 'articles') {
                                if ($actionable->article->status == 1) {
                                    $action->status = 1;
                                }
                            } else {
                                if ($actionable->faved_type == 'videos') {
                                    if ($actionable->video->article->status == 1) {
                                        $action->status = 1;
                                    }
                                }
                            }
                            break;
                        case 'follows':
                            if ($actionable->followed_type == 'categories') {
                                if ($actionable->category) {
                                    $action->status = 1;
                                }
                            } else {
                                if ($actionable->followed_type == 'collections') {
                                    if ($actionable->collection->status == 1) {
                                        $action->status = 1;
                                    }
                                } else {
                                    if ($actionable->followed_type == 'users') {
                                        if ($actionable->user) {
                                            $action->status = 1;
                                        }
                                    }
                                }
                            }
                            break;
                        case 'likes':
                            if ($actionable->liked_type == 'articles') {
                                if ($actionable->article->status) {
                                    $action->status = 1;
                                }
                            } else {
                                if ($actionable->liked_type == 'comments') {
                                    $comment = $actionable->comment;
                                    if ($comment->commentable_type == 'articles') {
                                        if ($comment->article->status == 1) {
                                            $action->status = 1;
                                        }
                                    }
                                }
                            }
                            break;
                    }

                    $fix_count++;

                    $this->info('action id.' . $action_id . ' fix success');
                } else {
                    $action->status = -1;
                    $delete_count++;
                    $this->error('action id.' . $action_id . ' fix failed');
                }
                $action->timestamps = false;
                $action->save();
            }
        });
        $this->info('fix action end');
    }

    public function users()
    {
        // 修复逻辑： 3小时内创建的账户（缩小影响范围 ），uuid有旧的存在：
        $users = User::where('created_at', '>', now()->addHours(-3))->get();
        $this->info('3小时内新用户数:' . $users->count());
        foreach ($users as $user) {
            $uuid = $user->uuid ? $user->uuid : $user->account;
            $this->info("uuid:" . $uuid);

            $qb = User::whereAccount($uuid);
            if ($qb->count() > 1) {
                $oldUser = $qb->orderBy('id')->first();
                if ($oldUser) {
                    $this->info("old users count:" . $qb->count());
                    $this->comment("找到旧用户 $oldUser->id $oldUser->uuid $oldUser->name account:$oldUser->account");

                    $user->uuid   = null;
                    $user->status = -1;
                    $user->save();

                    $oldUser->uuid = $uuid;
                    $oldUser->save();

                    $token           = $user->api_token;
                    $user->api_token = str_random(60);
                    $user->save();

                    $oldUser->api_token = $token;
                    $oldUser->save();
                }
            }
        }
        // -  清空uuid (account 已存)
        // -  账户停用

        // 下次启动... 新的token...  新的User ...   还得 me 查询里给切换回去...
        // -  找的uuid的 旧User, 更新uuid
        // -  新的停用账户换个token
        // -  覆盖token, 新的token 覆盖旧的token...

    }

    public function notifications()
    {
        $this->info('fix notifications ....');
        DB::table('notifications')->whereType('App\Notifications\ArticleLiked')->orderByDesc('id')->chunk(
            100,
            function ($notifications) {
                foreach ($notifications as $notification) {
                    $data = json_decode($notification->data);
                    if (strpos($data->body, '视频') !== false && strpos($data->title, '《》') !== false) {
                        try {
                            $article       = Article::findOrFail($data->article_id);
                            $article_title = $article->title ?: $article->video->title;
                            // 标题 视频标题都不存在 则取description
                            if (empty($article_title)) {
                                $article_title = $article->summary;
                            }
                            $notification->timestamps = false;
                            $result                   = DB::table('notifications')->where('id', $notification->id)
                                ->update(
                                    [
                                        'data->article_title' => $article_title,
                                        'data->title'         => '《' . $article_title . '》',
                                    ]
                                );
                            if ($result) {
                                $this->info('notification ' . $notification->id . ' fix success');
                            }
                        } catch (\Exception $e) {
                            continue;
                        }
                    }
                }
            }
        );
        $this->info('fix success');
    }

    public function transactions()
    {
        Transaction::whereType('打赏')->orderByDesc('id')->chunk(100, function ($transactions) {
            foreach ($transactions as $transaction) {
                if (strpos($transaction->remark, '赏元') !== false) {
                    $remark                  = str_replace('赏元', '赏' . intval($transaction->amount) . '元', $transaction->remark);
                    $transaction->remark     = $remark;
                    $transaction->timestamps = false;
                    $transaction->save();
                    $this->info('transaction ' . $transaction->id . ' fix success');
                }
            }
        });
    }

    public function withdrawJobs()
    {
        $count = 0;
        // $row   = DB::table('jobs')->where('queue', 'withdraws')->delete();
        // $this->info('提现队列JOB清理成功,清理数:' . $row);

        Withdraw::where('status', Withdraw::WAITING_WITHDRAW)->chunk(1000, function ($withdraws) use (&$count) {
            foreach ($withdraws as $withdraw) {
                dispatch(new \App\Jobs\ProcessWithdraw($withdraw->id))->onQueue('withdraws');
                $this->info('Withdraw Id:' . $withdraw->id . ' push queue success');
                $count++;
            }
        });

        $this->info('成功修复提现:' . $count);
    }

    public function payTest()
    {
        dump(class_exists('Yansongda\Pay\Pay'));
    }

    public function oAuths()
    {
        OAuth::where('oauth_type', 'wechat')->with('user')->chunk(1000, function ($oauths) {
            foreach ($oauths as $oauth) {
                $user   = $oauth->user;
                $wallet = $user->wallet;

                $payAccount = $wallet->pay_account;

                if (!is_email($payAccount) && !is_phone_number($payAccount)) {
                    $wallet->pay_account = null;
                    $wallet->save();
                }
            }
        });
    }

    public function wallets()
    {
        $data = Wallet::selectRaw('wechat_account,count(*) as bind_count')
            ->where('wechat_account', '!=', '')
            ->groupBy('wechat_account')
            ->having('bind_count', '>', 1)
            ->get();

        foreach ($data as $item) {
            $wechatAccount = $item->wechat_account;
            $wallets       = Wallet::where('wechat_account', $wechatAccount)->get();
            foreach ($wallets as $wallet) {
                $oauth = OAuth::where('user_id', $wallet->user_id)->where('oauth_type', 'wechat')->first();
                if (is_null($oauth)) {
                    $wallet->wechat_account = '';
                    $wallet->save();
                    $this->info('成功修复 Wallet Id:' . $wallet->id . ' 姓名:' . $wallet->real_name);
                }
            }
        }
    }

    public function spiders()
    {
        // $count    = 0;
        // $articles = \App\Article::where('source_url', 'like', '%v.douyin.com%')
        //     ->where('status', \App\Article::REVIEW_SUBMIT)
        //     ->chunk(1000, function ($artilces) use (&$count) {
        //         foreach ($artilces as $article) {
        //             $article->startSpider();
        //             $this->info('Article Id:' . $article->id . ' spider start');
        //             $count++;
        //         }
        //     });

        //这个hash有问题
        $hash  = 'd41d8cd98f00b204e9800998ecf8427e';
        $video = Video::where('hash', $hash)->first();
        if (!is_null($video)) {
            $count = Article::where('video_id', $video->id)->update(['status' => Article::REFUSED_SUBMIT, 'submit' => Article::REFUSED_SUBMIT]);
        }

        $this->info('本次成功修复待处理爬虫:' . $count);
    }
}
