<?php

declare(strict_types=1);

namespace App\Tests\Feature;

use App\Entity\Company;
use App\Tests\Support\HasAuthentication;
use App\Tests\TestCase;

class JWTTest extends TestCase
{
    use HasAuthentication;

    public function test_jwt_payload_data(): void
    {
        $this->factory->create(Company::class, [
            'email' => 'tony@starkindustries.com',
            'password' => 'lovepepper',
            'name' => 'Company Test',
            'logo' => 'logo.png',
        ]);

        ['data' => $data] = $this->authenticate([
            'username' => 'tony@starkindustries.com',
            'password' => 'lovepepper',
        ]);

        $this->assertIsInt($data['id']);
        $this->assertEquals('tony@starkindustries.com', $data['email']);
        $this->assertEquals('Company Test', $data['name']);
        $this->assertEquals('logo.png', $data['logo']);
    }
}
