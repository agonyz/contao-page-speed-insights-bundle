<?php

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Agonyz\ContaoPageSpeedInsightsBundle\Entity\AgonyzRequest;

/**
 * @method AgonyzRequest|null    find($id, $lockMode = null, $lockVersion = null)
 * @method AgonyzRequest|null    findOneBy(array $criteria, array $orderBy = null)
 * @method array|AgonyzRequest[] findAll()
 * @method array|AgonyzRequest[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgonyzRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AgonyzRequest::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function getLatestRequest(): ?AgonyzRequest
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function createRequestOrderedByLatestQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.id', 'DESC')
        ;
    }
}
