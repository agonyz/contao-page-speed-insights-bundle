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

use Agonyz\ContaoPageSpeedInsightsBundle\Entity\AgonyzRequest;
use Agonyz\ContaoPageSpeedInsightsBundle\Service\Request\CompareRequests;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

class RequestListController extends AbstractController
{
    private TwigEnvironment $twig;
    private EntityManagerInterface $entityManager;
    private int $requestPagination;

    public function __construct(TwigEnvironment $twig, EntityManagerInterface $entityManager, int $requestPagination)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
        $this->requestPagination = $requestPagination;
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights/request-list/{page<\d+>}",
     *     name="agonyz_contao_page_speed_insights_request_list",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function requestResults(int $page = 1): Response
    {
        $queryBuilder = $this->entityManager->getRepository(AgonyzRequest::class)->createRequestOrderedByLatestQueryBuilder();

        $pagerfanta = new Pagerfanta(new QueryAdapter($queryBuilder));
        $pagerfanta->setMaxPerPage($this->requestPagination);
        $pagerfanta->setCurrentPage($page);

        return new Response(
            $this->twig->render(
                '@AgonyzContaoPageSpeedInsights/request_list.html.twig',
                [
                    'pager' => $pagerfanta
                ]
            )
        );
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights/request/{id}/delete",
     *     name="agonyz_contao_page_speed_insights_by_id_delete",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function removeRequestById(int $id): JsonResponse
    {
        $request = $this->entityManager->getRepository(AgonyzRequest::class)->findOneBy(['id' => $id]);
        if(!$request) {
            return new JsonResponse('', Response::HTTP_BAD_REQUEST);
        }
        $this->entityManager->remove($request);
        $this->entityManager->flush();

        return new JsonResponse('', Response::HTTP_OK);
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights/request/{id}/compare",
     *     name="agonyz_contao_page_speed_insights_by_id_compare",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function compareRequests(int $id): Response
    {
        $request = $this->entityManager->getRepository(AgonyzRequest::class)->findOneBy(['id' => $id]);
        $latestRequest = $this->entityManager->getRepository(AgonyzRequest::class)->getLatestRequest();
        $comparison = CompareRequests::compare($request, $latestRequest);

        return new Response(
            $this->twig->render(
                '@AgonyzContaoPageSpeedInsights/request_comparison.html.twig',
                [
                    'comparison' => $comparison
                ]
            )
        );
    }
}
