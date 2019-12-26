<?php

namespace App\Helpers;

use anerg\OAuth2\OAuth as SnsOAuth;

class AlipayUtils
{
    public static function userInfo($code)
    {
        $userInfo          = [];
        $_GET['auth_code'] = $code;
        $config            = [
            'app_id'      => config('pay.alipay.app_id'),
            'scope'       => 'auth_user',
            'pem_private' => base_path('cert/alipay/pem/private.pem'),
            'pem_public'  => base_path('cert/alipay/pem/public.pem'),
        ];
        try {
            $snsOAuth = SnsOAuth::alipay($config);
            $userInfo = $snsOAuth->userinfoRaw();
        } catch (\Exception $ex) {
            $userInfo['errorMsg'] = $ex->getMessage();
        }

        return $userInfo;
    }
}
