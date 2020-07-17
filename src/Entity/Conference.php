<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use DateTime;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ConferenceRepository")
 * @UniqueEntity("slug")
 *
 * @ApiResource(
 *     collectionOperations={"get"={"normalization_context"={"groups"="conference:list"}}, "post"},
 *     itemOperations={"get"={"normalization_context"={"groups"="conference:item"}}},
 *     order={"startAt"="DESC", "city"="ASC"},
 *     paginationEnabled=true
 * )
 */
class Conference
{
    /**
     * @ApiProperty(identifier=false)
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id;

    /**
     * @Groups({"conference:list", "conference:item"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $name;

    /**
     * @Groups({"conference:item"})
     *
     * @ORM\Column(type="text")
     */
    private ?string $description;

    /**
     * @Groups({"conference:item"})
     *
     * @ORM\Column(type="string", length=255)
     */
    private ?string $location;

    /**
     * @Groups({"conference:list", "conference:item"})
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $startAt;

    /**
     * @Groups({"conference:item"})
     *
     * @ORM\Column(type="datetime")
     */
    private ?DateTime $endAt;

    /**
     * @ApiProperty(identifier=true)
     * @Groups({"conference:list", "conference:item"})
     *
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private ?string $slug;

    /**
     * @Groups({"conference:item"})
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $city;

    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->description = null;
        $this->location = null;
        $this->startAt = null;
        $this->endAt = null;
        $this->slug = '-';
        $this->city = null;
    }

    public function computeSlug(SluggerInterface $slugger): void
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string)$slugger->slug((string)$this)->lower();
        }
    }

    public function __toString(): string
    {
        return "{$this->name} {$this->city} {$this->startAt->format('Y')}";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Conference
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Conference
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): Conference
    {
        $this->description = $description;
        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(?string $location): Conference
    {
        $this->location = $location;
        return $this;
    }

    public function getStartAt(): ?DateTime
    {
        return $this->startAt;
    }

    public function setStartAt(?DateTime $startAt): Conference
    {
        $this->startAt = $startAt;
        return $this;
    }

    public function getEndAt(): ?DateTime
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTime $endAt): Conference
    {
        $this->endAt = $endAt;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }
}
