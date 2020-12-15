<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Cache;
use TencentCloud\Common\Credential;
use TencentCloud\Common\Exception\TencentCloudSDKException;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Vod\V20180717\Models\DescribeAllClassRequest;
use TencentCloud\Vod\V20180717\VodClient;

class VodController extends Controller
{

    const VALID_TIME = 60 * 30; //签名有效时间(S)

    private $vodKeys = [];

    public function __construct()
    {
        $fromCache = Cache::get('tencent_vod_class_ids', []);
        //加入缓存
        if (count($fromCache) > 0) {
            $this->vodKeys = $fromCache;
        }
        try {

            $cred        = new Credential(env("VOD_SECRET_ID"), env("VOD_SECRET_KEY"));
            $httpProfile = new HttpProfile();
            $httpProfile->setEndpoint("vod.tencentcloudapi.com");

            $clientProfile = new ClientProfile();
            $clientProfile->setHttpProfile($httpProfile);
            $client = new VodClient($cred, "ap-guangzhou", $clientProfile);

            $req = new DescribeAllClassRequest();

            $params = '{}';
            $req->fromJsonString($params);
            $resp    = $client->DescribeAllClass($req);
            $result  = json_decode($resp->toJsonString(), true);
            $vodKeys = Arr::pluck($result['ClassInfoSet'], 'ClassId', 'ClassName');

            Cache::put('tencent_vod_class_ids', $vodKeys, now()->addMinutes(10));
            $this->vodKeys = $vodKeys;
        } catch (TencentCloudSDKException $e) {
            abort(500, '服务器内部错误');
        }
    }

    const PROCEDURE = ''; //视频处理任务流

    /**
     * 获取VOD视频上传签名.
     *
     * @return string
     */
    public function signature($site)
    {
        $vodKeys = $this->vodKeys;
        $from    = $site;
        if (!$from || !array_key_exists($from, $vodKeys)) {
            abort(500, '输入参数有误');
        }

        $secret_id  = env("VOD_SECRET_ID");
        $secret_key = env("VOD_SECRET_KEY");

        // 确定签名的当前时间和失效时间
        $current = time();
        $expired = $current + self::VALID_TIME;

        // 向参数列表填入参数
        $arg_list = array(
            'secretId'         => $secret_id, //密钥中的 SecretId
            'classId'          => $vodKeys[$from], //视频文件分类
            'currentTimeStamp' => $current, //当前 Unix 时间戳
            'taskNotifyMode'   => 'Finish', //只有当任务流全部执行完毕时，才发起一次事件通知
            'notifyMode'       => 'Finish',
            'expireTime'       => $expired, //密钥中的 SecretId
            'random'           => rand(), //构造签名明文串的参数，无符号 32 位随机数
            //'oneTimeValid'  => 1,           //签名是否单次有效   0 表示不启用；1 表示签名单次有效
        );

        // 计算签名
        $original  = http_build_query($arg_list);
        $signature = base64_encode(hash_hmac('SHA1', $original, $secret_key, true) . $original);

        return $signature;
    }

}
