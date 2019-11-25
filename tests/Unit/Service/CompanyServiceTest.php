<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Tests\TestCase;
use App\Service\CompanyService;
use App\Request\CompanyRegistration;
use App\Tests\Support\PasswordEncoder;
use App\Repository\Contracts\CompanyRepository;

class CompanyServiceTest extends TestCase
{
    public function test_encode_password_on_save(): void
    {
        $mockRepository = $this->getMockBuilder(CompanyRepository::class)->getMock();
        $fakePasswordEncoder = new PasswordEncoder('encodedpassword');
        $service = new CompanyService($mockRepository, $fakePasswordEncoder);
        $registration  = $this->buildCompanyRegistration();
        
        $company = $service->register($registration);

        $this->assertEquals('encodedpassword', $company->getPassword());
    }

    private function buildCompanyRegistration(): CompanyRegistration
    {
        $registration = new CompanyRegistration();
        $registration->name = 'Acme, Inc.';
        $registration->email = 'acme@company.com';
        $registration->password = '123456';
        return $registration;
    }
}
