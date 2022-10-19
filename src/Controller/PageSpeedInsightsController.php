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
use Contao\CoreBundle\Exception\AccessDeniedException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Twig\Environment as TwigEnvironment;

class PageSpeedInsightsController extends AbstractController
{
    private TwigEnvironment $twig;
    private string $apiKey;
    private EntityManagerInterface $entityManager;
    private int $requestStatusRefreshRate;
    private Security $security;

    public function __construct(TwigEnvironment $twig, string $apiKey, EntityManagerInterface $entityManager, int $requestStatusRefreshRate, Security $security)
    {
        $this->twig = $twig;
        $this->apiKey = $apiKey;
        $this->entityManager = $entityManager;
        $this->requestStatusRefreshRate = $requestStatusRefreshRate;
        $this->security = $security;
    }

    /**
     * @Route("%contao.backend.route_prefix%/agonyz/page-speed-insights",
     *     name="agonyz_contao_page_speed_insights_main",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function index(): Response
    {
        if(!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('contao_user.agonyz_page_speed_insights', 'agonyz_page_speed_insights')) {
            throw new AccessDeniedException('Not enough permissions to access this controller.');
        }

        $latestRequest = $this->entityManager->getRepository(AgonyzRequest::class)->getLatestRequest();
        if (!$this->apiKey || !$latestRequest) {
            $pageSpeedInsights = 'error';
        }
        elseif (!$latestRequest->getRequestResults() && !$latestRequest->isRequestRunning() && !$latestRequest->isSuccessful()) {
            $pageSpeedInsights = 'error';
        }
        elseif (!$latestRequest->getRequestResults() && $latestRequest->isRequestRunning() && !$latestRequest->isSuccessful()) {
            $pageSpeedInsights = 'running';
        }
        else {
            return new Response(
                $this->twig->render(
                    '@AgonyzContaoPageSpeedInsights/page_speed_insights.html.twig',
                    [
                        'pageSpeedInsights' => $latestRequest->getRequestResults(),
                        'timestamp' => $latestRequest->getCreated(),
                        'requestStatusRefreshRate' => $this->requestStatusRefreshRate
                    ]
                )
            );
        }
        return new Response(
            $this->twig->render(
                '@AgonyzContaoPageSpeedInsights/page_speed_insights.html.twig',
                [
                    'pageSpeedInsights' => $pageSpeedInsights,
                    'requestStatusRefreshRate' => $this->requestStatusRefreshRate
                ]
            )
        );
    }

    /**
     * @Route("%contao.backend.route_prefix%/agonyz/page-speed-insights/request/{id}",
     *     name="agonyz_contao_page_speed_insights_by_id",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function requestById(int $id): Response
    {
        if(!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('contao_user.agonyz_page_speed_insights', 'agonyz_page_speed_insights')) {
            throw new AccessDeniedException('Not enough permissions to access this controller.');
        }

        $request = $this->entityManager->getRepository(AgonyzRequest::class)->findOneBy(['id' => $id]);
        {
            return new Response(
                $this->twig->render(
                    '@AgonyzContaoPageSpeedInsights/request_results_by_id.html.twig',
                    [
                        'pageSpeedInsights' => $request->getRequestResults(),
                        'timestamp' => $request->getCreated(),
                        'requestStatusRefreshRate' => $this->requestStatusRefreshRate
                    ]
                )
            );
        }
    }

    /**
     * @Route("%contao.backend.route_prefix%/agonyz/page-speed-insights/make-request",
     *     name="agonyz_contao_page_speed_insights_make_request",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function makeRequest()
    {
        if(!$this->security->isGranted('ROLE_ADMIN') && !$this->security->isGranted('contao_user.agonyz_page_speed_insights', 'agonyz_page_speed_insights')) {
            throw new AccessDeniedException('Not enough permissions to access this controller.');
        }

        return new JsonResponse('task started', Response::HTTP_OK);
    }

    /**
     * @Route("%contao.backend.route_prefix%/agonyz/page-speed-insights/request-progress",
     *     name="agonyz_contao_page_speed_insights_request_progress",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function requestProgress()
    {
        $latestRequest = $this->entityManager->getRepository(AgonyzRequest::class)->getLatestRequest();

        if(!$latestRequest) {
            $data['requestDone'] = true;
            return new JsonResponse($data, Response::HTTP_OK);
        }

        $data['requestFinalCount'] = $latestRequest->getRequestFinalCount();
        $data['requestCounter'] = $latestRequest->getRequestCounter();
        $data['requestRunning'] = $latestRequest->isRequestRunning();
        $data['requestDone'] = false;

        if($data['requestFinalCount'] === $data['requestCounter'] && $data['requestRunning'] === false)  {
            $data['requestDone'] = true;
        }
        return new JsonResponse($data, Response::HTTP_OK);
    }
}
