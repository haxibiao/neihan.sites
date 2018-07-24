<?php

namespace App\Http\Controllers\Api;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class shareController extends Controller
{
    public function shareWechat(Request $request)
    {
        // $data       = [];
        // $url        = $request->get('url');
        // $article_id = $request->get('article_id');

        // $share_file_dir = '/storage/share/' . $article_id . 'weChat_share.png';
        // $share_dir      = public_path('/storage/share/');
        // $public_share   = $share_dir . $article_id . 'weChat_share.png';

        // if (!is_dir($share_dir)) {
        //     mkdir($share_dir, 0777, 1);
        // }

        // $qrCode_image = file_exists($public_share) ? $qrCode_image = $share_file_dir : QrCode::encoding('UTF-8')->format('png')->size(400)->generate($url, $public_share);

        // if (empty($qrCode_image)) {
        //     dd("二维码截取错误,请重试");
        // }

        // $data['share_file_dir'] = $share_file_dir;
        // return $share_file_dir;
        // 这种方式存在问题
        if($request->get('url'))
        {
            $url = $request->get('url');
            return QrCode::size(400)->generate($url);
        }
        return QrCode::size(400)->generate('https://www.ainicheng.com/');
    }
}
