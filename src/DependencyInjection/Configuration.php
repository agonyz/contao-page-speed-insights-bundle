<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('agonyz_contao_page_speed_insights');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
            ->scalarNode('api_key')
            ->info('The api key for google page speed insights (https://developers.google.com/speed/docs/insights/v5/get-started)')
            ->cannotBeEmpty()
            ->defaultValue('')
            ->end()
            ->integerNode('request_retries')
            ->info('How often should each site be requested to get determine the request result')
            ->defaultValue(3)
            ->end()
            ->integerNode('pool_request_concurrency')
            ->info('This value determines how many asynchronous requests can be send at the same time')
            ->defaultValue(10)
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
