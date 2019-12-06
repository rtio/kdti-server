<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class PostJobOffer
{
    /**
     * @Assert\NotBlank()
     */
    public $title;

    /**
     * @Assert\NotBlank()
     */
    public $description;

    /**
     * @Assert\NotBlank()
     */
    public $seniorityLevel;

    /**
     * @Assert\NotBlank()
     * @Assert\Positive()
     */
    public $salary;
}
