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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class AgonyzContaoPageSpeedInsightsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('commands.yml');
        $loader->load('listeners.yml');
        $loader->load('controllers.yml');
        $loader->load('repositories.yml');

        // Configuration
        $container->setParameter('agonyz_contao_page_speed_insights.api_key', $config['api_key']);
        $container->setParameter('agonyz_contao_page_speed_insights.cache_ttl', $config['cache_ttl']);
        $container->setParameter('agonyz_contao_page_speed_insights.request_retries', $config['request_retries']);
        $container->setParameter('agonyz_contao_page_speed_insights.cache_key', $config['cache_key']);
        $container->setParameter('agonyz_contao_page_speed_insights.pool_request_concurrency', $config['pool_request_concurrency']);
    }
}
