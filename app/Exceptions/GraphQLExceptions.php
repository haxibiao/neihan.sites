<?php
namespace App\Exceptions;
use App\Exceptions\MessageError;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Exception;
use Folklore\GraphQL\Error\ValidationError;
use Illuminate\Support\Arr;
use \App\Exceptions\UnregisteredException;

class GraphQLExceptions {
	//不需要通知的异常放在下面
	protected static $dontReport = [
		\App\Exceptions\UnregisteredException::class, //用户未登录异常
		\App\Exceptions\ValidationExcetion::class, //自定义校验异常
		\Illuminate\Database\Eloquent\ModelNotFoundException::class, //模型找不到异常
	];

	public static function formatError(Exception $e) {
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
		} elseif (!($previous && $previous instanceof MessageError)) {
			if (env('APP_ENV') == 'prod') {
				if (self::shouldReport($previous)) {
					\Bugsnag::notifyException($e);
				}
			}
			//日志文件记录详细的日志信息
			report($e);
		}
		return $error;
	}

	protected static function shouldReport(Exception $e) {
		return is_null(Arr::first(self::$dontReport, function ($type) use ($e) {
			return $e instanceof $type;
		}));
	}
}