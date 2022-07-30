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

use Contao\Model\Collection;
use Contao\PageModel;

class GetDomainUrls
{
    public function getDomainUrls(): ?array
    {
        if (!($rootPages = $this->getRootPages())) {
            return null;
        }

        $domainUrls = [];

        /**
         * @var PageModel $rootPage
         */
        foreach ($rootPages as $rootPage) {
            $protocol = 'https';

            if (!$rootPage->dns) {
                continue;
            }

            if (!$rootPage->useSSL) {
                $protocol = 'http';
            }
            $domainUrls[$rootPage->dns] = sprintf('%s://%s', $protocol, $rootPage->dns);
        }

        return $domainUrls;
    }

    private function getRootPages(): ?Collection
    {
        $rootPages = PageModel::findBy(['type = ?', 'published = ?', 'activatePageSpeedInsights = ?'], ['root', 1, 1], ['return' => 'Collection']);

        if (!isset($rootPages) || 0 === $rootPages->count()) {
            return null;
        }

        return $rootPages;
    }
}
