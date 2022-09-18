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
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Twig\Environment as TwigEnvironment;

class PageSpeedInsightsController extends AbstractController
{
    private TwigEnvironment $twig;
    private string $apiKey;
    private EntityManagerInterface $entityManager;

    public function __construct(TwigEnvironment $twig, string $apiKey, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->apiKey = $apiKey;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights",
     *     name=PageSpeedInsightsController::class,
     *     defaults={"_scope": "backend"}
     * )
     */
    public function index(): Response
    {
        $latestRequest = $this->entityManager->getRepository(AgonyzRequest::class)->getLatestRequest();
        if (!$this->apiKey || !$latestRequest) {
            $pageSpeedInsights = 'error';
        }
        elseif (!$latestRequest->getRequestResults() && !$latestRequest->isRequestRunning() && !$latestRequest->isSuccessful()) {
            $pageSpeedInsights = 'error';
        }
        else {
            return new Response(
                $this->twig->render(
                    '@AgonyzContaoPageSpeedInsights/page_speed_insights.twig.html',
                    [
                        'pageSpeedInsights' => $latestRequest->getRequestResults(),
                        'timestamp' => $latestRequest->getCreated()
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

    /**
     * @Route("/contao/agonyz/page-speed-insights/make-request",
     *     name="agonyz_contao_page_speed_insights_make_request",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function makeRequest()
    {
        return new JsonResponse('task started', Response::HTTP_OK);
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights/request-progress",
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
