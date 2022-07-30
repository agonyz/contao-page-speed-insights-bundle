<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Cron;

use Agonyz\ContaoPageSpeedInsightsBundle\Service\RequestCacheHandler;
use Contao\CoreBundle\Framework\ContaoFramework;

class PageSpeedInsightsCron
{
    private ContaoFramework $contaoFramework;
    private RequestCacheHandler $requestCacheHandler;

    public function __construct(ContaoFramework $contaoFramework, RequestCacheHandler $requestCacheHandler)
    {
        $this->contaoFramework = $contaoFramework;
        $this->requestCacheHandler = $requestCacheHandler;
    }

    public function __invoke(): void
    {
        $this->contaoFramework->initialize();
        $this->requestCacheHandler->deleteCacheKey();
        $this->requestCacheHandler->createCacheKey();
    }
}
