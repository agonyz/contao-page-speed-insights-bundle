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

use Contao\CoreBundle\Event\MenuEvent;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Contracts\Translation\TranslatorInterface;
use Terminal42\ServiceAnnotationBundle\Annotation\ServiceTag;

/**
 * @ServiceTag("kernel.event_listener", event="contao.backend_menu_build", priority=-255)
 */
class BackendMenuListener
{
    private $router;
    private $requestStack;
    private $security;
    private $translator;

    public function __construct(RouterInterface $router, RequestStack $requestStack, Security $security, TranslatorInterface $translator)
    {
        $this->router = $router;
        $this->requestStack = $requestStack;
        $this->security = $security;
        $this->translator = $translator;
    }

    public function __invoke(MenuEvent $event): void
    {
        $tree = $event->getTree();

        if ('mainMenu' !== $tree->getName()) {
            return;
        }

        if (!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('contao_user.agonyz_page_speed_insights', 'agonyz_page_speed_insights')) {
            return;
        }

        $this->addBackendMenuEntry($event);
        $this->addMakeRequestNode($event);
        $this->addOverviewNode($event);
    }

    private function addBackendMenuEntry(MenuEvent $event): void
    {
        $tree = $event->getTree();

        $agonyzNode = $event->getFactory()
            ->createItem('agonyz-page-speed-insights-menu-entry')
            ->setChildrenAttribute('id', 'agonyz-page-speed-insights-menu-entry')
            ->setUri('/contao?mtg=agonyz-page-speed-insights-menu-entry')
            ->setLinkAttribute('onclick', "return AjaxRequest.toggleNavigation(this, 'agonyz-page-speed-insights-menu-entry', '/contao')")
            ->setLabel($this->translator->trans('page_speed_insights', [], 'AgonyzContaoPageSpeedBundle'))
            ->setLinkAttribute('class', 'group-agonyz-page-speed-insights')
            ->setExtra('translation_domain', false)
        ;

        $tree->addChild($agonyzNode);
    }

    private function addMakeRequestNode(MenuEvent $event): void
    {
        $tree = $event->getTree();
        $parentNode = $tree->getChild('agonyz-page-speed-insights-menu-entry');

        $childNode = $event->getFactory()
            ->createItem('agonyz-page-speed-insights')
            ->setUri($this->router->generate('agonyz_contao_page_speed_insights_main'))
            ->setLabel($this->translator->trans('make_request', [], 'AgonyzContaoPageSpeedBundle'))
            ->setLinkAttribute('title', $this->translator->trans('make_request_title', [], 'AgonyzContaoPageSpeedBundle'))
            ->setLinkAttribute('class', 'agonyz-page-speed-insights')
            ->setCurrent('agonyz_contao_page_speed_insights_main' === $this->requestStack->getCurrentRequest()->get('_controller'))
            ->setExtra('translation_domain', false)
        ;

        $parentNode->addChild($childNode);
    }

    private function addOverviewNode(MenuEvent $event): void
    {
        $tree = $event->getTree();
        $parentNode = $tree->getChild('agonyz-page-speed-insights-menu-entry');

        $childNode = $event->getFactory()
            ->createItem('agonyz-page-speed-insights-request-list')
            ->setUri($this->router->generate('agonyz_contao_page_speed_insights_request_list'))
            ->setLabel($this->translator->trans('overview', [], 'AgonyzContaoPageSpeedBundle'))
            ->setLinkAttribute('title', $this->translator->trans('overview_title', [], 'AgonyzContaoPageSpeedBundle'))
            ->setLinkAttribute('class', 'agonyz-page-speed-insights')
            ->setCurrent('agonyz_contao_page_speed_insights_request_list' === $this->requestStack->getCurrentRequest()->get('_controller'))
            ->setExtra('translation_domain', false)
        ;

        $parentNode->addChild($childNode);
    }
}
