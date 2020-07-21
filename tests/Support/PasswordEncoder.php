<?php

declare(strict_types=1);

namespace App\Tests\Support;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class PasswordEncoder implements UserPasswordEncoderInterface
{
    private $hash;

    public function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public function encodePassword(UserInterface $user, $plainPassword): string
    {
        return $this->hash;
    }

    public function isPasswordValid(UserInterface $user, $raw): bool
    {
        return true;
    }

    public function needsRehash(UserInterface $user): bool
    {
        return false;
    }
}
