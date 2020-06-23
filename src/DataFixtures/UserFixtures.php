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
        $user = new StaffUser();
        $user->setEmail('user@user.com');

        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '12345'
        ));

        $manager->persist($user);
        $manager->flush();
    }
}
