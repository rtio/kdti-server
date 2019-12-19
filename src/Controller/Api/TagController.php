<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Repository\TagRepository;
use App\Controller\BaseController;
use DateTime;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

final class TagController extends BaseController
{
    private $repository;
    private $paginator;

    public function __construct(
        TagRepository $repository,
        PaginatorInterface $paginator
    ) {
        $this->repository = $repository;
        $this->paginator = $paginator;
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @Route("/api/tags/{tagId}", name="api_tag_display", methods={"GET"})
     */
    public function display(Request $request, int $tagId): Response
    {
        $tags = $this->paginator->paginate(
            $this->repository->findAllTaggedJobsApprovedById($tagId),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->json($tags, Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['list'],
            AbstractNormalizer::CALLBACKS => ['publishedAt' => static function ($innerObject) {
                return $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : '';
            }],
        ]);
    }

    /**
     * @IsGranted("IS_AUTHENTICATED_ANONYMOUSLY")
     *
     * @Route("/api/tags/slug/{slug}", name="api_tag_display_by_slug", methods={"GET"})
     */
    public function displayBySlug(Request $request, string $slug): Response
    {
        $tags = $this->paginator->paginate(
            $this->repository->findAllTaggedJobsApprovedBySlug($slug),
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 10)
        );

        return $this->json($tags, Response::HTTP_OK, [], [
            AbstractNormalizer::IGNORED_ATTRIBUTES => ['company' => 'jobOffers'],
            AbstractNormalizer::GROUPS => ['list'],
            AbstractNormalizer::CALLBACKS => ['publishedAt' => static function ($innerObject) {
                return $innerObject instanceof DateTime ? $innerObject->format(DateTime::ISO8601) : '';
            }],
        ]);
    }
}
