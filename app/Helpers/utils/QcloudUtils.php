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

    public static function getTaskInfo($taskId)
    {
        $params = [
            'vodTaskId' => $taskId,
        ];
        return self::retryVodApi('GetTaskInfo', $params);
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

    public static function getVideoInfo($fileId)
    {
        $params = [
            'fileId' => $fileId,
        ];
        return self::retryVodApi('GetVideoInfo', $params);
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
            'fileId'                            => $fileId,
            'snapshotByTimeOffset.definition'   => 10,
            'snapshotByTimeOffset.timeOffset.1' => 1000,
            'coverBySnapshot.definition'        => 10,
            'coverBySnapshot.positionType'      => 'Time',
            'coverBySnapshot.position'          => 2, // 第2秒
            // 'sampleSnapshot.definition' => 20043,
            // 'animatedGraphics.definition' => 20000,
            // 'animatedGraphics.startTime' => 3,
            // 'animatedGraphics.endTime' => 5,
        ];
        return self::retryVodApi('ProcessFile', $params);
    }

    public static function makeCoverAndSnapshots($fileId, $duration = null)
    {
        $maxDuration = $duration ?: 9;
        $timeOffsets = [];
        for ($d = 1; $d <= $maxDuration; $d++) {
            $timeOffsets['snapshotByTimeOffset.timeOffset.' . $d] = $d * 1000;
        }
        $params = array_merge([
            'fileId'                          => $fileId,
            'snapshotByTimeOffset.definition' => 10, //截取9张正常缩放的图片
        ],
            $timeOffsets,
            [
                'coverBySnapshot.definition'   => 10, //截取封面
                'coverBySnapshot.positionType' => 'Time',
                'coverBySnapshot.position'     => 2, // 第2秒
            ]);
        return self::retryVodApi('ProcessFile', $params);
    }

    public static function makeLanscapeCoverAndSnapshots($fileId)
    {
        $params = [
            'fileId'                            => $fileId,
            'snapshotByTimeOffset.definition'   => 20080, //这个模板设置为截图300*200， 手机视频肯定被压扁了
            'snapshotByTimeOffset.timeOffset.1' => 1000,
            'snapshotByTimeOffset.timeOffset.2' => 2000,
            'snapshotByTimeOffset.timeOffset.3' => 3000,
            'snapshotByTimeOffset.timeOffset.4' => 4000,
            'snapshotByTimeOffset.timeOffset.5' => 5000,
            'snapshotByTimeOffset.timeOffset.6' => 6000,
            'snapshotByTimeOffset.timeOffset.7' => 7000,
            'snapshotByTimeOffset.timeOffset.8' => 8000,
            'snapshotByTimeOffset.timeOffset.9' => 9000,
            'coverBySnapshot.definition'        => 10,
            'coverBySnapshot.positionType'      => 'Time',
            'coverBySnapshot.position'          => 2, // 第2秒
        ];
        return self::retryVodApi('ProcessFile', $params);
    }

    public static function genGif($fileId)
    {
        $params = [
            'fileId'                        => $fileId,
            'animatedGraphics.definition.2' => 20000,
            'animatedGraphics.startTime'    => 0,
            'animatedGraphics.endTime'      => 2,
        ];
        return self::retryVodApi('ProcessFile', $params);
    }

    public static function simpleProcessFile($fileId)
    {
        $params = [
            'file.id'   => $fileId,
            'inputType' => 'SingleFile',
            'procedure' => 'QCVB_SimpleProcessFile(1, 1, 10, 10)', //这个系统预置流程简单实用，转码，水印，封面，截图
        ];
        return self::retryVodApi('RunProcedure', $params);
    }

    public static function takeSnapshotsByTime($fileId)
    {
        $params = [
            'fileId'       => $fileId,
            'definition'   => 10, //正常比例缩放
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
