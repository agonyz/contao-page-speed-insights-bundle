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
use Agonyz\ContaoPageSpeedInsightsBundle\Repository\AgonyzRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Cache\CacheItemPoolInterface;

class RequestCacheHandler
{
    private GetDomainResults $getDomainResults;
    private int $cacheTtl;
    private string $cacheKey;
    private CacheItemPoolInterface $cacheApp;
    private RequestDatabaseHandler $requestDatabaseHandler;

    public function __construct(GetDomainResults $getDomainResults, int $cacheTtl, string $cacheKey, CacheItemPoolInterface $cacheApp, RequestDatabaseHandler $requestDatabaseHandler)
    {
        $this->getDomainResults = $getDomainResults;
        $this->cacheTtl = $cacheTtl;
        $this->cacheKey = $cacheKey;
        $this->cacheApp = $cacheApp;
        $this->requestDatabaseHandler = $requestDatabaseHandler;
    }

    public function deleteCacheKey(): void
    {
        $this->requestDatabaseHandler->setRequestRunning(false);
        $cache = $this->cacheApp;

        if ($cache->hasItem($this->cacheKey)) {
            $cache->delete($this->cacheKey);
        }
    }

    public function createCacheKey(): bool
    {
        $this->requestDatabaseHandler->createRequestCheck();
        $this->requestDatabaseHandler->setRequestRunning(true);

        $cache = $this->cacheApp;
        $result = $cache->getItem($this->cacheKey);
        if(!$this->cacheTtl) {
            $result->expiresAfter(null);
        }
        else {
            $result->expiresAfter($this->cacheTtl);
        }

        dump($this->getDomainResults->getDomainResults());

        if (!($domainResults = $this->getDomainResults->getDomainResults())) {
            $result->set('error');
            $cache->save($result);
            $this->requestDatabaseHandler->setRequestRunning(false);

            return false;
        }

        $data = [
            'domainResults' => $domainResults,
            'timestamp' => (new \DateTime('now'))->getTimestamp()
        ];

        $result->set($data);
        $cache->save($result);
        $this->requestDatabaseHandler->setRequestRunning(false);

        return true;
    }
}
