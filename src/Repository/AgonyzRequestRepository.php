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
}
