<?php

declare(strict_types=1);

namespace App\Input;

use App\Entity\JobOffer;
use App\Exception\ValidatorConstraintException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class JobOfferInput
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(
        ValidatorInterface $validator,
        TokenStorageInterface $tokenStorage
    ) {
        $this->validator = $validator;
        $this->tokenStorage = $tokenStorage;
    }

    public function fromCreate(array $data): JobOffer
    {
        $jobOffer = new JobOffer();
        $this->hidrate($jobOffer, $data);

        $violations = $this->validator->validate($jobOffer);
        if ($violations->count()) {
            throw new ValidatorConstraintException($violations);
        }

        return $jobOffer;
    }

    protected function hidrate(JobOffer $jobOffer, array $data): void
    {
        $user = $this->tokenStorage->getToken()->getUser();
        $jobOffer->setCompany($user);
        $jobOffer->setStatus(JobOffer::STATUS_PENDING_REVIEW);
        $jobOffer->setHiringType(JobOffer::HIRING_TYPE_CLT);

        if (isset($data['title'])) {
            $jobOffer->setTitle($data['title']);
        }

        if (isset($data['description'])) {
            $jobOffer->setDescription($data['description']);
        }

        if (isset($data['seniorityLevel'])) {
            $jobOffer->setSeniorityLevel($data['seniorityLevel']);
        }

        if (isset($data['minimumSalary'])) {
            $jobOffer->setMinimumSalary((int) $data['minimumSalary']);
        }

        if (isset($data['maximumSalary'])) {
            $jobOffer->setMaximumSalary((int) $data['maximumSalary']);
        }

        if (isset($data['allowRemote'])) {
            $jobOffer->setAllowRemote((bool) $data['allowRemote']);
        }
    }
}
