<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\JobOffer;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class JobOfferEntityListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(JobOffer $tag, LifecycleEventArgs $event)
    {
        $tag->computeSlug($this->slugger);
    }

    public function preUpdate(JobOffer $tag, LifecycleEventArgs $event)
    {
        $tag->computeSlug($this->slugger);
    }
}
