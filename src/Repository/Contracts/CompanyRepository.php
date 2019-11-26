<?php

declare(strict_types=1);

namespace App\Repository\Contracts;

use App\Entity\Company;

interface CompanyRepository
{
    public function save(Company $company): void;
}
