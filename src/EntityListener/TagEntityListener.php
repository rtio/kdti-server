<?php

declare(strict_types=1);

namespace App\EntityListener;

use App\Entity\Tag;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

class TagEntityListener
{
    private SluggerInterface $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function prePersist(Tag $tag, LifecycleEventArgs $event)
    {
        $tag->computeSlug($this->slugger);
    }

    public function preUpdate(Tag $tag, LifecycleEventArgs $event)
    {
        $tag->computeSlug($this->slugger);
    }
}
