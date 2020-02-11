<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Throwable;

class ValidatorException extends RuntimeException
{
    protected $errors;

    public function __construct(array $errors, $code = 400, Throwable $previous = null)
    {
        parent::__construct('', $code, $previous);
        $this->errors = $errors;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
