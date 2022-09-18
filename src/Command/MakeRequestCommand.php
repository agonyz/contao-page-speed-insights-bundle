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

use Agonyz\ContaoPageSpeedInsightsBundle\Service\Request\RequestHandler;
use Contao\CoreBundle\Framework\ContaoFramework;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MakeRequestCommand extends Command
{
    protected static $defaultName = 'agonyz-page-speed-insights:request';
    protected static $defaultDescription = 'Makes requests to google page speeed insights api';

    private ContaoFramework $contaoFramework;
    private RequestHandler $requestHandler;

    public function __construct(ContaoFramework $contaoFramework, RequestHandler $requestHandler)
    {
        $this->contaoFramework = $contaoFramework;
        $this->requestHandler = $requestHandler;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->contaoFramework->initialize();
        $io = new SymfonyStyle($input, $output);

        if (!$this->requestHandler->request()) {
            $io->error('Could not make request to google page speed insights api. Please check your domain dns configuration.');

            return Command::FAILURE;
        }
        $io->success('Successfully made request to google page speed insights api.');

        return Command::SUCCESS;
    }
}
