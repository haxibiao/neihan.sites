<?php
namespace App\Exceptions;
use App\Exceptions\MessageError;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Folklore\GraphQL\Error\ValidationError;

class GraphQLExceptions {
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
		//TODO 后面可以考虑把自定义的异常(比如登录异常)吃掉,不提交到BugSnag，太浪费资源了。
		$previous = $e->getPrevious();
		//是否存在校验异常
		if ($previous && $previous instanceof ValidationError) {
			$error['validation'] = $previous
				->getValidatorMessages();
		} elseif (!($previous && $previous instanceof MessageError)) {
			if (\App::environment('prod')) {
				if (app()->bound('sentry') && $this->shouldReport($exception)) {
					app('sentry')->captureException($exception);

					//not always report to bugsnag in prod , too many 404 ....
					\Bugsnag::notifyException($exception);
				}
			}
		}
		return $error;
	}
}