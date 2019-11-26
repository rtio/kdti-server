<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\Company;
use App\Request\CompanyRegistration;
use App\Repository\Contracts\CompanyRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

final class CompanyService
{
    private $repository;
    private $encoder;

    public function __construct(
        CompanyRepository $repository,
        UserPasswordEncoderInterface $encoder
    ) {
        $this->repository = $repository;
        $this->encoder = $encoder;
    }

    public function register(CompanyRegistration $registration): Company
    {
        $company = Company::createFromRegistration($registration);
        
        $password = $this->encoder->encodePassword($company, $registration->password);
        $company->setPassword($password);

        $this->repository->save($company);

        return $company;
    }
}
