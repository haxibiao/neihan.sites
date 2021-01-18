<?php
namespace App\Exceptions;

// use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Folklore\GraphQL\Error\ValidationError;
use Haxibiao\Breeze\Exceptions\UnregisteredException;
use Illuminate\Support\Arr;

class GraphQLExceptions
{
    //不需要通知的异常放在下面
    protected static $dontReport = [
        UnregisteredException::class, //用户未登录异常
        \App\Exceptions\ValidationExcetion::class, //自定义校验异常
        \Illuminate\Database\Eloquent\ModelNotFoundException::class, //模型找不到异常
    ];

    public static function formatError(Exception $e)
    {
        $error = [
            'message' => $e->getMessage(),
        ];
        $locations = $e->getLocations();
        if (!empty($locations)) {
            $error['locations'] = array_map(function ($loc) {
                return $loc->toArray();
            }, $locations);
        }
        $previous = $e->getPrevious();
        if ($previous && $previous instanceof ValidationError) {
            $error['validation'] = $previous
                ->getValidatorMessages();
        }
//        elseif (!($previous && $previous instanceof MessageError)) {
        //            if (is_prod()) {
        //                if (self::shouldReport($previous)) {
        //                    // \Bugsnag::notifyException($e);
        //                }
        //            }
        //        }
        //Log::debug('gql error: ' . json_encode($error));
        return $error;
    }

    protected static function shouldReport(Exception $e)
    {
        return is_null(Arr::first(self::$dontReport, function ($type) use ($e) {
            return $e instanceof $type;
        }));
    }
}
