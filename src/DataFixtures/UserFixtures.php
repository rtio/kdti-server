<?php


namespace App\DataFixtures;


use App\Entity\StaffUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $manager->persist($this->createRegularUser());
        $manager->persist($this->createAdminUser());
        $manager->flush();
    }

    private function createRegularUser(): StaffUser
    {
        $staffUser = new StaffUser();
        $staffUser->setEmail('user@user.com');

        $staffUser->setPassword($this->passwordEncoder->encodePassword(
            $staffUser,
            '12345'
        ));

        $staffUser->setRoles(['ROLE_USER']);

        return $staffUser;
    }

    private function createAdminUser(): StaffUser
    {
        $staffUser = new StaffUser();
        $staffUser->setEmail('admin@admin.com');

        $staffUser->setPassword($this->passwordEncoder->encodePassword(
            $staffUser,
            '12345'
        ));

        $staffUser->setRoles(['ROLE_ADMIN']);

        return $staffUser;
    }
}
