<?php

namespace App\Exceptions;

use App\Category;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(Throwable $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $exception)
    {
        // Convert all non-http exceptions to a proper 500 http exception
        // if we don't do this exceptions are shown as a default template
        // instead of our own view in resources/views/errors/500.blade.php
        if ($this->shouldReport($exception) && !$this->isHttpException($exception) && !config('app.debug')) {
            $exception = new HttpException(500, 'Whoops!');
        }

        //404 异常处理
        $e = $this->prepareException($exception);
        if ($this->isHttpException($e) && ($e->getStatusCode() == 404)) {
            $data['categories'] = Category::where('parent_id', 1)->orderByDesc('updated_at')->take(4)->get();
            //取最近七天点击量 倒叙
            $data['articles'] = \App\Article::with('video')->whereType('video')
                ->whereStatus(1)
                ->whereRaw('DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= date(created_at)')
                ->orderByDesc('hits')->take(4)->get();
            return response()->view('errors.404', ['data' => $data], 404);
        }
        return parent::render($request, $exception);
    }
}
