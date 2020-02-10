<?php

declare(strict_types=1);

namespace App\Entity;

use App\Request\CompanyRegistration;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 */
class Company implements JWTUserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"admin", "detail", "list"})
     */
    private ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"admin", "detail", "list"})
     */
    private ?string $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin", "detail", "list"})
     */
    private ?string $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"admin", "detail"})
     */
    private ?string $address;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     * @Groups({"admin", "detail"})
     */
    private ?string $email;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     * @Groups({"admin", "detail"})
     */
    private ?string $phoneNumber;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\JobOffer", mappedBy="company", orphanRemoval=true)
     * @Groups({"admin", "detail"})
     */
    private Collection $jobOffers;

    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->logo = null;
        $this->address = null;
        $this->email = null;
        $this->phoneNumber = null;
        $this->password = null;
        $this->jobOffers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return "#{$this->id} {$this->name}";
    }

    public static function createFromRegistration(CompanyRegistration $registration): self
    {
        return (new static())
            ->setName($registration->name)
            ->setEmail($registration->email);
    }

    public static function createFromPayload($username, array $payload)
    {
        return (new static())
            ->setEmail($username);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getJobOffers(): Collection
    {
        return $this->jobOffers;
    }

    public function addJobOffer(JobOffer $jobOffer): self
    {
        if (!$this->jobOffers->contains($jobOffer)) {
            $this->jobOffers[] = $jobOffer;
            $jobOffer->setCompany($this);
        }

        return $this;
    }

    public function removeJobOffer(JobOffer $jobOffer): self
    {
        if ($this->jobOffers->contains($jobOffer)) {
            $this->jobOffers->removeElement($jobOffer);
            // set the owning side to null (unless already changed)
            if ($jobOffer->getCompany() === $this) {
                $jobOffer->setCompany(null);
            }
        }

        return $this;
    }

    public function getRoles(): array
    {
        return ['ROLE_COMPANY'];
    }

    public function getSalt(): void
    {
    }

    public function getUsername(): ?string
    {
        return $this->email;
    }

    public function eraseCredentials(): void
    {
    }
}
