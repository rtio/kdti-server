<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class CompanyRegistration
{
    /**
     * @Assert\NotBlank()
     */
    public $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;
}
