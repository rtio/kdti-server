<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Tests\TestCase;
use App\Request\CompanyRegistration;

class CompanyTest extends TestCase
{
    public function test_string_representation(): void
    {
        $company = $this->factory->create(Company::class, [
            'name' => 'Dunder Mifflin',
            'hiring_type' => JobOffer::HIRING_TYPE_CLT,
        ]);

        $this->assertEquals("#{$company->getId()} Dunder Mifflin", (string) $company);
    }

    public function test_use_email_as_username(): void
    {
        $company = new Company();

        $company->setEmail('arya.stark@gmail.com');

        $this->assertEquals('arya.stark@gmail.com', $company->getUsername());
    }

    public function test_company_user_roles(): void
    {
        $company = new Company();

        $this->assertEquals(['ROLE_COMPANY'], $company->getRoles());
    }

    public function test_create_a_company_from_a_registration(): void
    {
        $registration = $this->buildCompanyRegistration();

        $company = Company::createFromRegistration($registration);

        $this->assertEquals('Acme, Inc.', $company->getName());
        $this->assertEquals('acme@company.com', $company->getEmail());
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
