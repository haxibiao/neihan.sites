<?php

namespace App\Exceptions;

use Exception;
use Nuwave\Lighthouse\Exceptions\RendersErrorsExtensions;

class GQLException extends Exception implements RendersErrorsExtensions
{
    /**
     * @var @string
     */
    private $reason;

    /**
     * CustomException constructor.
     *
     * @param  string  $message
     * @param  string  $reason
     * @return void
     */
    public function __construct(string $message,int $code= 1 ,string $reason = "")
    {
        parent::__construct($message);

        $this->reason   = $reason;
        $this->code     = $code;
    }

    /**
     * Returns true when exception message is safe to be displayed to a client.
     *
     * @api
     * @return bool
     */
    public function isClientSafe(): bool
    {
        return true;
    }

    /**
     * Returns string describing a category of the error.
     *
     * Value "graphql" is reserved for errors produced by query parsing or validation, do not use it.
     *
     * @api
     * @return string
     */
    public function getCategory(): string
    {
        return 'custom';
    }

    /**
     * Return the content that is put in the "extensions" part
     * of the returned error.
     *
     * @return array
     */
    public function extensionsContent(): array
    {
        return [
            'from'   => 'lighthouse-php',
            'reason' => $this->reason,
        ];
    }
}
