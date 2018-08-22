<?php
namespace App\Exceptions;
use App\Exceptions\MessageError;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Folklore\GraphQL\Error\ValidationError;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Arr;

class GraphQLExceptions  {
	//忽略下列异常
	protected static $dontReport = [
		\App\Exceptions\UnregisteredException::class,//用户未登录异常
	];

	public static function formatError(\Exception $e) {
		//错误消息体
		$error = [
			'message' => $e->getMessage(),
		];
		$locations = $e->getLocations();
		if (!empty($locations)) {
			$error['locations'] = array_map(function ($loc) {
				return $loc->toArray();
			}, $locations);
		}
		//返回异常堆栈信息中的前一个异常
		$previous = $e->getPrevious();
		//是否存在校验异常
		if ($previous && $previous instanceof ValidationError) {
			$error['validation'] = $previous
				->getValidatorMessages();
		} elseif (!($previous && $previous instanceof MessageError)) {
			if (env('APP_ENV') == 'prod') {
				if (!self::shouldReport($e)) {
					\Bugsnag::notifyException($e);
				}
			}
		}
		return $error;
	}

	protected static function shouldReport(\Exception $e) {
		return is_null(Arr::first(self::$dontReport, function ($type) use ($e) {
			return $e instanceof $type;
		}));
	}
}