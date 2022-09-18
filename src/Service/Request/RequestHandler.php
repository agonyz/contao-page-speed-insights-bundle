<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Service\Request;

use Agonyz\ContaoPageSpeedInsightsBundle\Entity\AgonyzRequest;
use Doctrine\ORM\EntityManagerInterface;
use Agonyz\ContaoPageSpeedInsightsBundle\Service\GetDomainResults;

class RequestHandler
{
    private GetDomainResults $getDomainResults;
    private EntityManagerInterface $entityManager;

    public function __construct(GetDomainResults $getDomainResults, EntityManagerInterface $entityManager)
    {
        $this->getDomainResults = $getDomainResults;
        $this->entityManager = $entityManager;
    }

    public function request(): bool
    {
        $request = new AgonyzRequest();
        $request->setRequestRunning(true);
        $request->setRequestFinalCount(0);
        $request->setRequestCounter(0);
        $request->setCreated((new \DateTime()));
        $request->setSuccessful(null);
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        if (!($domainResults = $this->getDomainResults->getDomainResults($request))) {
            $request->setRequestRunning(false);
            $request->setSuccessful(false);
            $this->entityManager->persist($request);
            $this->entityManager->flush();
            return false;
        }

        $request->setRequestResults($domainResults);
        $request->setRequestRunning(false);
        $request->setSuccessful(true);
        $this->entityManager->persist($request);
        $this->entityManager->flush();

        return true;
    }
}
