<?php

declare(strict_types=1);

namespace App\QueryExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\JobOffer;
use Doctrine\ORM\QueryBuilder;

final class ActiveJobOffersExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== JobOffer::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.status = :status', $rootAlias))
            ->setParameter('status', JobOffer::STATUS_APPROVED)
            ->orderBy(sprintf('%s.publishedAt', $rootAlias), 'ASC');
    }
}
