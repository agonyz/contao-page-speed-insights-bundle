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

use Psr\Cache\CacheItemPoolInterface;

class RequestCacheHandler
{
    private GetDomainResults $getDomainResults;
    private int $cacheTtl;
    private string $cacheKey;
    private RequestDatabaseHandler $requestDatabaseHandler;
    private CacheItemPoolInterface $cacheApp;

    public function __construct(GetDomainResults $getDomainResults, int $cacheTtl, string $cacheKey, RequestDatabaseHandler $requestDatabaseHandler, CacheItemPoolInterface $cacheApp)
    {
        $this->getDomainResults = $getDomainResults;
        $this->cacheTtl = $cacheTtl;
        $this->cacheKey = $cacheKey;
        $this->requestDatabaseHandler = $requestDatabaseHandler;
        $this->cacheApp = $cacheApp;
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

        if (!($domainResults = $this->getDomainResults->getDomainResults())) {
            $result->set('error');
            $cache->save($result);
            $this->requestDatabaseHandler->setRequestRunning(false);

            return false;
        }
        $result->set($domainResults);
        $cache->save($result);
        $this->requestDatabaseHandler->setRequestRunning(false);

        return true;
    }
}
