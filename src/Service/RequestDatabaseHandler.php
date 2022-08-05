<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Service;

use Agonyz\ContaoPageSpeedInsightsBundle\Entity\AgonyzRequest;
use Doctrine\ORM\EntityManagerInterface;

class RequestDatabaseHandler
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createRequestCheck(): void
    {
        $requestChecker = $this->entityManager->getRepository(AgonyzRequest::class)->findAll();

        if (!$requestChecker) {
            $requestCheck = new AgonyzRequest();
            $requestCheck->setRequestRunning(false);
            $this->entityManager->persist($requestCheck);
            $this->entityManager->flush();
        }
    }

    public function isRequestRunning(): bool
    {
        if(!($request = $this->getAgonyzRequest())) {
            return false;
        }
        if (!$request->isRequestRunning()) {
            return false;
        }
        return true;
    }

    public function setRequestRunning(bool $status): bool
    {
        if(!($request = $this->getAgonyzRequest())) {
            return false;
        }
        $request->setRequestRunning($status);
        $this->entityManager->persist($request);
        $this->entityManager->flush();
        return true;
    }

    private function getAgonyzRequest(): ?AgonyzRequest
    {
        $request = $this->entityManager->getRepository(AgonyzRequest::class)->findAll();

        if(!$request) {
            return null;
        }

        if(count($request) > 1) {
            return null;
        }
        return $request[0];
    }
}
