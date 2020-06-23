<?php


namespace App\DataFixtures;


use App\Entity\Company;use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CompanyFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createRegularCompany());
        $manager->flush();
    }

    private function createRegularCompany(): Company
    {
        $company = new Company();
        $company
            ->setName('Dunder Mifflin')
            ->setEmail('contact@dunder-mifflin.com')
            ->setAddress('1725 Slough Avenue in Scranton, PA')
            ->setLogo('logo.png')
            ->setPhoneNumber('2345678888');

        $company->setPassword($this->passwordEncoder->encodePassword(
            $company,
            '12345'
        ));

        return $company;
    }
}
