<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Controller;

use Agonyz\ContaoPageSpeedInsightsBundle\Service\RequestDatabaseHandler;
use Psr\Cache\CacheItemPoolInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

class PageSpeedInsightsController extends AbstractController
{
    private TwigEnvironment $twig;
    private string $cacheKey;
    private string $apiKey;
    private RequestDatabaseHandler $requestDatabaseHandler;
    private CacheItemPoolInterface $cacheApp;

    public function __construct(TwigEnvironment $twig, string $cacheKey, string $apiKey, RequestDatabaseHandler $requestDatabaseHandler, CacheItemPoolInterface $cacheApp)
    {
        $this->twig = $twig;
        $this->cacheKey = $cacheKey;
        $this->apiKey = $apiKey;
        $this->requestDatabaseHandler = $requestDatabaseHandler;
        $this->cacheApp = $cacheApp;
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights",
     *     name=PageSpeedInsightsController::class,
     *     defaults={"_scope": "backend"}
     * )
     */
    public function index(): Response
    {
        $cache = $this->cacheApp;
        $result = $cache->getItem($this->cacheKey);

        if (!$this->apiKey) {
            $pageSpeedInsights = 'error';
        } elseif (!$result->isHit()) {
            if (!$this->requestDatabaseHandler->isRequestRunning()) {
                $pageSpeedInsights = 'restart';
            } else {
                $pageSpeedInsights = 'running';
            }
        } else {
            $pageSpeedInsights = $result->get();

            return new Response(
                $this->twig->render(
                    '@AgonyzContaoPageSpeedInsights/page_speed_insights.twig.html',
                    [
                        'pageSpeedInsights' => $pageSpeedInsights['domainResults'],
                        'timestamp' => $pageSpeedInsights['timestamp']
                    ]
                )
            );
        }

        return new Response(
            $this->twig->render(
                '@AgonyzContaoPageSpeedInsights/page_speed_insights.twig.html',
                [
                    'pageSpeedInsights' => $pageSpeedInsights,
                ]
            )
        );
    }
}
