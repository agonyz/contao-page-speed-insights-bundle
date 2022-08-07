<?php

namespace Agonyz\ContaoPageSpeedInsightsBundle\EventListener;

use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Routing\RouterInterface;
use Agonyz\ContaoPageSpeedInsightsBundle\Service\RequestCacheHandler;

class TerminateRequestListener
{
    private RouterInterface $router;
    private RequestCacheHandler $requestCacheHandler;

    public function __construct(RouterInterface $router, RequestCacheHandler $requestCacheHandler)
    {
        $this->router = $router;
        $this->requestCacheHandler = $requestCacheHandler;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $currentRoute = $this->router->match($event->getRequest()->getPathInfo());
        if ('agonyz_contao_page_speed_insights_make_request' === $currentRoute['_route']) {
            $this->requestCacheHandler->deleteCacheKey();
            $this->requestCacheHandler->createCacheKey();
        }
    }
}
