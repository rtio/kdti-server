<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobOfferRepository")
 */
final class JobOffer
{
    const STATUS_PENDING_REVIEW = 'PENDING_REVIEW';
    const STATUS_APPROVED = 'APPROVED';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $seniorityLevel;

    /**
     * @ORM\Column(type="integer")
     */
    private $salary;

    /**
     * @ORM\Column(type="string", length=14)
     */
    private $status;

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

    public function __toString(): string
    {
        return "#{$this->id} {$this->title}";
    }
}
