<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\EventListener;

use Agonyz\ContaoPageSpeedInsightsBundle\Service\Request\RequestHandler;
use Contao\CoreBundle\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Event\TerminateEvent;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;

class TerminateRequestListener
{
    private RouterInterface $router;
    private RequestHandler $requestHandler;
    private Security $security;

    public function __construct(RouterInterface $router, RequestHandler $requestHandler, Security $security)
    {
        $this->router = $router;
        $this->requestHandler = $requestHandler;
        $this->security = $security;
    }

    public function onKernelTerminate(TerminateEvent $event): void
    {
        $currentRoute = $this->router->match($event->getRequest()->getPathInfo());

        if ('agonyz_contao_page_speed_insights_make_request' === $currentRoute['_route']) {
            if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('contao_user.agonyz_page_speed_insights', 'agonyz_page_speed_insights')) {
                throw new AccessDeniedException('Not enough permissions to access this controller.');
            }

            $this->requestHandler->request();
        }
    }
}
