<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Company;
use App\Request\PostJobOffer;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobOfferRepository")
 */
class JobOffer
{
    const STATUS_PENDING_REVIEW = 'PENDING_REVIEW';
    const STATUS_APPROVED = 'APPROVED';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * 
     * @Groups({"admin", "detail", "list"})
     */
    private $id;

    /**
     * @Gedmo\Slug(fields={"title"}, updatable=false)
     * 
     * @ORM\Column(type="string", length=100, unique=true)
     * 
     * @Groups({"admin", "detail", "list"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=50)
     * 
     * @Groups({"admin", "detail", "list"})
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     * 
     * @Groups({"admin", "detail"})
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups({"admin", "detail", "list"})
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=10)
     * 
     * @Groups({"admin", "detail", "list"})
     */
    private $seniorityLevel;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Groups({"admin", "detail", "list"})
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=14)
     * 
     * @Groups({"admin"})
     */
    private $status;

    public function __toString(): string
    {
        return "#{$this->id} {$this->title}";
    }

    public static function createFromPost(PostJobOffer $data, Company $company): self
    {
        return (new static)
            ->setCompany($company)
            ->setTitle($data->title)
            ->setDescription($data->description)
            ->setSeniorityLevel($data->seniorityLevel)
            ->setSalary($data->salary)
            ->setStatus(static::STATUS_PENDING_REVIEW)
        ;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getSeniorityLevel(): ?string
    {
        return $this->seniorityLevel;
    }

    public function setSeniorityLevel(string $seniorityLevel): self
    {
        $this->seniorityLevel = $seniorityLevel;

        return $this;
    }

    public function getSalary(): ?int
    {
        return $this->salary;
    }

    public function setSalary(int $salary): self
    {
        $this->salary = $salary;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
