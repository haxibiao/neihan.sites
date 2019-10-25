<?php
namespace App\Helpers\Pay\Alipay;

class Transfer
{
    protected $method = 'alipay.fund.trans.toaccount.transfer';
    protected $appId;
    protected $privateKey;
    protected $charset;
    protected $config = [];

    public function __construct($appid, $privateKey, $charset = 'utf8')
    {
        $this->charset    = 'utf8';
        $this->appId      = $appid;
        $this->privateKey = $privateKey;
        $this->config     = $this->generateConfig();
    }

    /**
     * 支付转账
     *
     * @return array
     */
    public function doPay()
    {
        $this->generateConfigSign();
        $result    = $this->curlPost('https://openapi.alipay.com/gateway.do', $this->config);
        $resultArr = json_decode($result, true);
        if (empty($resultArr)) {
            $result = iconv('GBK', 'UTF-8//IGNORE', $result);
            return json_decode($result, true);
        }
        return $resultArr;
    }

    /**
     * 构建转账请求公共参数
     *
     * @return array
     */
    public function generateConfig()
    {
        //公共参数
        $config = [
            'app_id'    => $this->appId,
            'method'    => $this->method,
            'format'    => 'JSON',
            'charset'   => $this->charset,
            'sign_type' => 'RSA2',
            'timestamp' => date('Y-m-d H:i:s'),
            'version'   => '1.0',
        ];
        return $config;
    }

    /**
     * 构建转账签名
     *
     * @return string
     */
    public function generateConfigSign()
    {
        $signContent                 = $this->getSignContent($this->config);
        return $this->config['sign'] = $this->sign($signContent, $this->config['sign_type']);
    }

    /**
     * 生成签名
     *
     * @param [type] $data
     * @param string $signType
     * @return string
     */
    protected function sign($data, $signType = "RSA")
    {
        $priKey = $this->privateKey;
        $res    = "-----BEGIN RSA PRIVATE KEY-----\n" .
        wordwrap($priKey, 64, "\n", true) .
            "\n-----END RSA PRIVATE KEY-----";

        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            //OPENSSL_ALGO_SHA256是php5.4.8以上版本才支持
            openssl_sign($data, $sign, $res, version_compare(PHP_VERSION, '5.4.0', '<') ? SHA256 : OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }

    /**
     * 检测值非空
     *
     * @param [type] $value
     * @return void
     */
    protected function checkEmpty($value)
    {
        if (!isset($value)) {
            return true;
        }

        if ($value === null) {
            return true;
        }

        if (trim($value) === "") {
            return true;
        }

        return false;
    }

    /**
     * 获取签名内容
     *
     * @param array $params
     * @return string
     */
    public function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i                = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {
                // 转换成目标字符集
                $v = $this->characet($v, $this->charset);
                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset($k, $v);
        return $stringToBeSigned;
    }

    /**
     * 转换字符集编码
     * @param $data
     * @param $targetCharset
     * @return string
     */
    public function characet($data, $targetCharset)
    {
        if (!empty($data)) {
            $fileType = $this->charset;
            if (strcasecmp($fileType, $targetCharset) != 0) {
                $data = mb_convert_encoding($data, $targetCharset, $fileType);
                //$data = iconv($fileType, $targetCharset.'//IGNORE', $data);
            }
        }
        return $data;
    }

    /**
     * 发送curl请求
     *
     * @param string $url
     * @param string $postData
     * @param array $options
     * @return void
     */
    public function curlPost($url = '', $postData = '', $options = array())
    {
        if (is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置cURL允许执行的最长秒数
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        //https请求 不验证证书和host
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    /**
     * 设置业务内容
     *
     * @param array $content
     * @return array
     */
    public function setBizContent($content = [])
    {
        return $this->config['biz_content'] = json_encode($content);
    }
}
