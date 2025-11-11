<?php

namespace App\Repository;

use App\Entity\VideoLink;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<VideoLink>
 */
class VideoLinkRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VideoLink::class);
    }

    /**
     * @return array{0: VideoLink[], 1: int}
     */
    public function paginateByStatus(int $page, int $perPage, ?string $status): array
    {
        $page = max(1, $page);
        $perPage = max(1, min(100, $perPage));

        $qb = $this->createBaseQueryBuilder();
        $this->applyStatusFilter($qb, $status);

        $countQb = $this->createBaseQueryBuilder(true);
        $this->applyStatusFilter($countQb, $status);

        $qb->setFirstResult(($page - 1) * $perPage)
            ->setMaxResults($perPage);

        $items = $qb->getQuery()->getResult();
        $total = (int) $countQb->select('COUNT(l.id)')->getQuery()->getSingleScalarResult();

        return [$items, $total];
    }

    private function createBaseQueryBuilder(bool $countOnly = false): QueryBuilder
    {
        $qb = $this->createQueryBuilder('l')
            ->orderBy('l.createdAt', 'DESC');

        if (!$countOnly) {
            $qb->leftJoin('l.video', 'v')
               ->addSelect('v');
        }

        return $qb;
    }

    private function applyStatusFilter(QueryBuilder $qb, ?string $status): void
    {
        $now = new \DateTimeImmutable();
        switch ($status) {
            case 'expired':
                $qb->andWhere('l.expiresAt IS NOT NULL AND l.expiresAt <= :now')
                   ->setParameter('now', $now);
                break;
            case 'clicked':
                $qb->andWhere('l.expiresAt IS NOT NULL');
                break;
            case 'all':
                // no filtering
                break;
            case 'active':
            default:
                $qb->andWhere('(l.expiresAt IS NULL OR l.expiresAt > :now)')
                   ->setParameter('now', $now);
                break;
        }
    }
}
