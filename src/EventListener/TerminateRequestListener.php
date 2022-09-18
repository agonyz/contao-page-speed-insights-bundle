<?php

namespace Agonyz\ContaoPageSpeedInsightsBundle\EventListener;

use Agonyz\ContaoPageSpeedInsightsBundle\Service\Request\RequestHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Routing\RouterInterface;

class TerminateRequestListener
{
    private RouterInterface $router;
    private RequestHandler $requestHandler;

    public function __construct(RouterInterface $router, RequestHandler $requestHandler)
    {
        $this->router = $router;
        $this->requestHandler = $requestHandler;
    }

    public function onKernelTerminate(TerminateEvent $event)
    {
        $currentRoute = $this->router->match($event->getRequest()->getPathInfo());
        if ('agonyz_contao_page_speed_insights_make_request' === $currentRoute['_route']) {
            $this->requestHandler->request();
        }
    }
}
