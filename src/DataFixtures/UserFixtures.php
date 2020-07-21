<?php


namespace App\DataFixtures;


use App\Entity\Staff;
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
        $manager->persist($this->createAdminUser());
        $manager->flush();
    }

    private function createAdminUser(): Staff
    {
        $staffUser = new Staff();
        $staffUser->setUsername('admin');

        $staffUser->setPassword($this->passwordEncoder->encodePassword(
            $staffUser,
            'admin'
        ));

        $staffUser->setRoles(['ROLE_ADMIN']);

        return $staffUser;
    }
}
