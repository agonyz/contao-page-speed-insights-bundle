<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Command;

use Agonyz\ContaoPageSpeedInsightsBundle\Service\RequestCacheHandler;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class DeleteCachedResultsCommand extends Command
{
    protected static $defaultName = 'agonyz-page-speed-insights:delete-cached-results';
    protected static $defaultDescription = 'Deletes the cached results for the page speed insights requests.';

    private ContaoFramework $contaoFramework;
    private RequestCacheHandler $requestCacheHandler;

    public function __construct(ContaoFramework $contaoFramework, RequestCacheHandler $requestCacheHandler)
    {
        $this->contaoFramework = $contaoFramework;
        $this->requestCacheHandler = $requestCacheHandler;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->contaoFramework->initialize();
        $io = new SymfonyStyle($input, $output);

        $this->requestCacheHandler->deleteCacheKey();
        $io->success('Successfully deleted cached page speed insights request results.');

        return Command::SUCCESS;
    }
}
