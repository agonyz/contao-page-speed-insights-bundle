<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\ContaoManager;

use Agonyz\ContaoPageSpeedInsightsBundle\AgonyzContaoPageSpeedInsightsBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(AgonyzContaoPageSpeedInsightsBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }

    /**
     * @throws \Exception
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        $file = __DIR__.'/../Resources/config/routes.yml';

        return $resolver->resolve($file)->load($file);
    }
}
