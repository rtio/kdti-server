<?php


namespace App\DataPersister;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\JobOffer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class JobOfferDataPersister implements DataPersisterInterface
{
    private EntityManagerInterface $entityManager;
    private Security $security;


    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function supports($data): bool
    {
        return $data instanceof JobOffer;
    }

    /**
     * @param JobOffer $data
     */
    public function persist($data, array $context = [])
    {
        if (!$data->getCompany() && $this->security->getUser()) {
            $data->setCompany($this->security->getUser());
        }

        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function remove($data)
    {
        $this->entityManager->remove($data);
        $this->entityManager->flush();
    }
}
