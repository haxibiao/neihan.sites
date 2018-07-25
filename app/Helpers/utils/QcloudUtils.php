<?php

namespace App\Helpers;

use QcloudApi;

/**
 * 腾讯云相关工具类
 */
class QcloudUtils
{
    private static $secretId;
    private static $secretKey;
    private static $logPath;

    private static function initVod()
    {
        $config = [
            'SecretId'      => config('qcloudcos.secret_id'),
            'SecretKey'     => config('qcloudcos.secret_key'),
            'RequestMethod' => 'POST',
        ];
        return QcloudApi::load(QcloudApi::MODULE_VOD, $config);
    }

    private static function retryVodApi($apiAction, $params)
    {
        $vod = self::initVod();
        for ($retry = 0; $retry < 3; $retry++) {
            if ($retry > 0) {
                echo "$apiAction retry at " . $retry;
            }
            $response = $vod->$apiAction($params);
            if ($response == false) {
                $error = $vod->getError();
                echo "$apiAction failed, code: " . $error->getCode() . 
                    ", message: " . $error->getMessage() . 
                    "ext: " . var_export($error->getExt(), true) . "\n";
                continue;
            } else {
                return $response;
            }
        }
    }

    public static function getTaskList()
    {
        $params = [
            'status' => "WAITING",
        ];
        $results['WAITING'] = self::retryVodApi('GetTaskList', $params);
        $params             = [
            'status' => "PROCESSING",
        ];
        $results['PROCESSING'] = self::retryVodApi('GetTaskList', $params);
        $params                = [
            'status' => "FINISH",
        ];
        $results['FINISH'] = self::retryVodApi('GetTaskList', $params);

        return $results;
    }

    public static function deleteVodFile($fileId)
    {
        $params = [
            'fileId'     => $fileId,
            'isFlushCdn' => 1,
            'priority'   => 1,
        ];
        return self::retryVodApi('DeleteVodFile', $params);
    }

    public static function getVodInfoByFileName($fileName)
    {
        $params = [
            'fileName' => $fileName,
        ];
        return self::retryVodApi('DescribeVodPlayInfo', $params);
    }

    public static function processVodFile($fileId)
    {
        $params = [
            'file.id'   => $fileId,
            'inputType' => 'SingleFile',
            'procedure' => 'QCVB_SimpleProcessFile(1, 1, 10)',
        ];
        return self::retryVodApi('RunProcedure', $params);
    }

    public static function takeVodSnapshots($fileId)
    {
        $params = [
            'fileId'       => $fileId,
            'definition'   => 10,
            'timeOffset.1' => 1000,
            'timeOffset.2' => 2000,
            'timeOffset.3' => 3000,
            'timeOffset.4' => 4000,
            'timeOffset.5' => 5000,
            'timeOffset.6' => 6000,
            'timeOffset.7' => 7000,
            'timeOffset.8' => 8000,
            'timeOffset.9' => 9000,
        ];
        return self::retryVodApi('CreateSnapshotByTimeOffset', $params);
    }

    public static function pullEvents()
    {
        $params = [];
        return self::retryVodApi('PullEvent', $params);
    }

    public static function confirmEvents($msgHandles = [])
    {
        $params = [];
        $i      = 0;
        foreach ($msgHandles as $msgHandle) {
            $params['msgHandle.' . $i] = $msgHandle;
            $i++;
        }
        return self::retryVodApi('ConfirmEvent', $params);
    }
}
