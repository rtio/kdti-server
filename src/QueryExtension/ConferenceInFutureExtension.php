<?php

declare(strict_types=1);

namespace App\QueryExtension;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Conference;
use DateTime;
use Doctrine\ORM\QueryBuilder;

final class ConferenceInFutureExtension implements QueryCollectionExtensionInterface
{
    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        if ($resourceClass !== Conference::class) {
            return;
        }

        $rootAlias = $queryBuilder->getRootAliases()[0];
        $queryBuilder->andWhere(sprintf('%s.startAt > :today', $rootAlias))
            ->setParameter('today', new DateTime())
            ->orderBy(sprintf('%s.startAt', $rootAlias), 'DESC');
    }
}
