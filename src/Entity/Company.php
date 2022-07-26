<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompanyRepository")
 *
 * @ApiResource(
 *     collectionOperations={
 *          "get"={
 *              "normalization_context"={"groups"="company:list"}
 *          },
 *          "post"={
 *              "normalization_context"={"groups"="company:item"},
 *              "denormalization_context"={"groups"="company:write"},
 *              "validation_groups"={"Default", "create"}
 *          },
 *     },
 *     itemOperations={"get"={"normalization_context"={"groups"="company:item"}}},
 *     paginationEnabled=true,
 * )
 */
class Company implements JWTUserInterface
{
    /**
     * @Groups({"company:item", "company:list"})
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @Groups({"company:item", "company:list", "company:write"})
     * @Assert\NotBlank(groups={"create"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name = null;

    /**
     * @Groups({"company:item", "company:list"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $logo = null;

    /**
     * @Groups({"company:item"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $address = null;

    /**
     * @Groups({"company:item", "company:write"})
     * @Assert\Email()
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $email = null;

    /**
     * @Groups({"company:item"})
     *
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private ?string $phoneNumber = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $password = null;

    /**
     * @Groups({"company:write"})
     * @SerializedName("password")
     * @Assert\NotBlank(groups={"create"})
     * @Assert\Length(min=6)
     */
    private ?string $plainPassword = null;

    /**
     * @ApiSubresource
     * @Groups({"company:item"})
     *
     * @ORM\OneToMany(targetEntity="App\Entity\JobOffer", mappedBy="company", orphanRemoval=true)
     */
    private Collection $jobOffers;

    public function __construct()
    {
        $this->jobOffers = new ArrayCollection();
    }

    public static function createFromPayload($username, array $payload)
    {
        return (new static())
            ->setEmail($username);
    }

    public function __toString(): string
    {
        return "{$this->name}";
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
        $this->plainPassword = null;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }
}
