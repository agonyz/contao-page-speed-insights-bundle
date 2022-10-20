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

use Agonyz\ContaoPageSpeedInsightsBundle\Service\Request\RequestHandler;
use Contao\CoreBundle\Framework\ContaoFramework;

class PageSpeedInsightsCron
{
    private ContaoFramework $contaoFramework;
    private RequestHandler $requestHandler;

    public function __construct(ContaoFramework $contaoFramework, RequestHandler $requestHandler)
    {
        $this->contaoFramework = $contaoFramework;
        $this->requestHandler = $requestHandler;
    }

    public function __invoke(): void
    {
        $this->contaoFramework->initialize();
        $this->requestHandler->request();
    }
}
