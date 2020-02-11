<?php

declare(strict_types=1);

namespace App\Exception;

use RuntimeException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidatorConstraintException extends RuntimeException
{
    public function __construct(ConstraintViolationListInterface $violationList, $code = 400, Throwable $previous = null)
    {
        parent::__construct('', $code, $previous);
        throw new ValidatorException($this->getErrorMessages($violationList), 400, $this);
    }

    protected function getErrorMessages(ConstraintViolationListInterface $violationList)
    {
        $errors = [];

        foreach ($violationList->getIterator() as $item) {
            $errors[$item->getPropertyPath()][] = $item->getMessage();
        }

        return $errors;
    }
}
