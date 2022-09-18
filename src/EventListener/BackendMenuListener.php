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

use Agonyz\ContaoPageSpeedInsightsBundle\Controller\PageSpeedInsightsController;
use Contao\CoreBundle\Event\MenuEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @ServiceTag("kernel.event_listener", event="contao.backend_menu_build", priority=-255)
 */
class BackendMenuListener
{
    protected $router;
    protected $requestStack;

    public function __construct(RouterInterface $router, RequestStack $requestStack)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
    }

    public function __invoke(MenuEvent $event): void
    {
        $factory = $event->getFactory();
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return;
        }

        $contentNode = $tree->getChild('system');

        $node = $factory
            ->createItem('agonyz-page-speed-insights')
            ->setUri($this->router->generate('agonyz_contao_page_speed_insights_main'))
            ->setLabel('Pagespeed-Insights')
            ->setLinkAttribute('title', 'Pagespeed Insights für die Root-Seiten ansehen')
            ->setLinkAttribute('class', 'agonyz-page-speed-insights')
            ->setCurrent('agonyz_contao_page_speed_insights_main' === $this->requestStack->getCurrentRequest()->get('_controller'))
        ;

        $contentNode->addChild($node);
    }
}
