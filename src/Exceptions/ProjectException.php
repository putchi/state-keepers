<?php

namespace Putchi\StateKeepers\Exceptions;

use Throwable;

class ProjectException extends CustomException {

    /**
     * ProjectException constructor.
     *
     * @param string|null    $message
     * @param string|null    $type
     * @param int            $code
     * @param array|null     $data
     * @param Throwable|null $previous
     */
    public function __construct(string $message = null, string $type = null, int $code = 500, array $data = null, Throwable $previous = null) {
        parent::__construct($message, $type, $code, $data, $previous);
    }
}