<?php


namespace App\Exceptions;

use Closure;
use Exception;
use GraphQL\Error\Debug;
use GraphQL\Error\Error;
use GraphQL\Error\FormattedError;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;
use Nuwave\Lighthouse\Execution\ErrorHandler;
use Sentry\State\Scope;
use Throwable;


class LighthouseErrorHandler implements ErrorHandler
{
    protected static $dontReport = [
        \Haxibiao\Base\Exceptions\GQLException::class,
        \App\Exceptions\GQLException::class,
        \App\Exceptions\UserException::class,
        \App\Exceptions\UnregisteredException::class,
        \App\Exceptions\ValidationExcetion::class,
        \Illuminate\Auth\AuthenticationException::class,
    ];

    public static function handle(Error $error, Closure $next): array
    {
        $underlyingException = $error->getPrevious();

        if($underlyingException){
            if (self::shouldReport($underlyingException)) {
                self::reportSentry($error);
            }

            if ($underlyingException instanceof RendersErrorsExtensions) {
                $error = new Error(
                    $error->message,
                    $error->nodes,
                    $error->getSource(),
                    $error->getPositions(),
                    $error->getPath(),
                    $underlyingException,
                    $underlyingException->extensionsContent()
                );
            }
        }

        return $next($error);
    }

    protected static function reportSentry($error)
    {
        if (app()->bound('sentry')) {
            app('sentry')->withScope(function (Scope $scope) use ($error) {
                $scope->setExtra('details', FormattedError::createFromException($error, Debug::INCLUDE_DEBUG_MESSAGE));
                $scope->setExtra('clientSafe', $error->isClientSafe());

                app('sentry')->captureException($error);
            });
        }
    }

    protected static function shouldReport(Throwable $e)
    {
        return is_null(Arr::first(self::$dontReport, function ($type) use ($e) {
            return $e instanceof $type;
        }));
    }
}