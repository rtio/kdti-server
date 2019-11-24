<?php

declare(strict_types=1);

namespace App\Tests\Unit\Entity;

use App\Entity\Company;
use App\Tests\TestCase;

class CompanyTest extends TestCase
{

    public function test__toString()
    {
        $company = $this->factory->create(Company::class, [
            'name' => 'Dunder Mifflin',
        ]);

        $this->assertEquals("#{$company->getId()} Dunder Mifflin", (string) $company);
    }
}
