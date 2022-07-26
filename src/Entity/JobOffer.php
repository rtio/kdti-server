<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\JobOfferRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity("slug")
 *
 * @ApiResource(
 *     collectionOperations={
 *          "get"={"normalization_context"={"groups"="joboffer:list"}},
 *          "post"={
 *              "denormalization_context"={"groups"="joboffer:write"},
 *              "security"="is_granted('ROLE_COMPANY')"
 *          }
 *      },
 *     itemOperations={
 *          "get"={"normalization_context"={"groups"="joboffer:item"}},
 *          "put"={"security_post_denormalize"="is_granted('ROLE_COMPANY') and previous_object.getCompany() == user"},
 *          "delete"={"security_post_denormalize"="is_granted('ROLE_COMPANY') and previous_object.getCompany() == user"}
 *     },
 *     paginationEnabled=true,
 *     subresourceOperations={
 *          "api_tags_job_offers_get_subresource"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"joboffer:list"}}
 *          },
 *          "api_companies_job_offers_get_subresource"={
 *              "method"="GET",
 *              "normalization_context"={"groups"={"joboffer:list"}}
 *          }
 *     }
 * )
 */
class JobOffer
{
    public const STATUS_PENDING_REVIEW = 'PENDING_REVIEW';

    /**
     * @var string
     */
    public const STATUS_APPROVED = 'APPROVED';

    public const HIRING_TYPE_CLT = 'CLT';

    /**
     * @var string
     */
    public const HIRING_TYPE_PJ = 'PJ';

    /**
     * @ApiProperty(identifier=false)
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ApiProperty(identifier=true)
     * @Groups({"joboffer:item", "joboffer:list"})
     *
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private ?string $slug = null;

    /**
     * @Groups({"joboffer:item", "joboffer:list", "joboffer:write"})
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=50)
     */
    private ?string $title = null;

    /**
     * @Groups({"joboffer:item", "joboffer:write"})
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="text")
     */
    private ?string $description = null;

    /**
     * @Groups({"joboffer:item", "joboffer:list", "joboffer:write"})
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Company", inversedBy="jobOffers")
     * @ORM\JoinColumn(nullable=true)
     */
    private ?Company $company = null;

    /**
     * @Groups({"joboffer:item", "joboffer:list", "joboffer:write"})
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=10)
     */
    private ?string $seniorityLevel = null;

    /**
     * @Groups({"joboffer:item", "joboffer:list", "joboffer:write"})
     * @Assert\NotBlank()
     * @Assert\Positive()
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    private ?int $minimumSalary = 0;

    /**
     * @Groups({"joboffer:item", "joboffer:list", "joboffer:write"})
     * @Assert\NotBlank()
     * @Assert\Positive()
     *
     * @ORM\Column(type="integer", nullable=false, options={"unsigned":true, "default":0})
     */
    private ?int $maximumSalary = 0;

    /**
     * @Groups({"joboffer:item", "joboffer:list"})
     *
     * @ORM\Column(type="string", length=14)
     */
    private ?string $status = self::STATUS_PENDING_REVIEW;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private DateTime $updatedAt;

    /**
     * @Groups({"joboffer:item"})
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?DateTime $publishedAt = null;

    /**
     * @Groups({"joboffer:item", "joboffer:list", "joboffer:write"})
     *
     * @ORM\Column(type="string", length=3)
     */
    private ?string $hiringType = null;

    /**
     * @Groups({"joboffer:item", "joboffer:write"})
     * @Assert\Type(type="bool")
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private bool $allowRemote = false;

    /**
     * @Groups({"joboffer:item", "joboffer:write"})
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Tag", mappedBy="jobOffers")
     */
    private Collection $tags;

    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->updatedAt = new DateTime();
        $this->tags = new ArrayCollection();
    }

    public function __toString(): string
    {
        return sprintf('%s - %s - %s', $this->company->getName(), $this->seniorityLevel, $this->title);
    }

    public function computeSlug(SluggerInterface $slugger): void
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string)$slugger->slug((string)$this)->lower();
        }
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

    public function getMinimumSalary(): ?int
    {
        return $this->minimumSalary;
    }

    public function setMinimumSalary(int $minimumSalary): self
    {
        $this->minimumSalary = $minimumSalary;

        return $this;
    }

    public function getMaximumSalary(): ?int
    {
        return $this->maximumSalary;
    }

    public function setMaximumSalary(int $maximumSalary): self
    {
        $this->maximumSalary = $maximumSalary;

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

    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue()
    {
        $this->createdAt = new DateTime();
    }

    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new DateTime();
    }

    public function getPublishedAt(): ?DateTime
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?DateTime $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function isAllowRemote(): ?bool
    {
        return $this->allowRemote;
    }

    public function setAllowRemote(bool $allowRemote): self
    {
        $this->allowRemote = $allowRemote;

        return $this;
    }

    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addJobOffer($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->contains($tag)) {
            $this->tags->removeElement($tag);
            $tag->removeJobOffer($this);
        }

        return $this;
    }

    public function getHiringType(): ?string
    {
        return $this->hiringType;
    }

    public function setHiringType(string $hiringType): self
    {
        $this->hiringType = $hiringType;

        return $this;
    }
}
