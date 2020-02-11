<?php

declare(strict_types=1);

namespace App\Output;

use App\Entity\JobOffer;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class JobOfferOutput
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    public function toFullOutput(JobOffer $jobOffer, string $format = OutputFormat::JSON)
    {
        return $this->serializer->serialize($jobOffer, $format, [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['detail'],
        ]);
    }
}
