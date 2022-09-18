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

class RequestListController extends AbstractController
{
    private TwigEnvironment $twig;
    private EntityManagerInterface $entityManager;

    public function __construct(TwigEnvironment $twig, EntityManagerInterface $entityManager)
    {
        $this->twig = $twig;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/contao/agonyz/page-speed-insights/request-list",
     *     name="agonyz_contao_page_speed_insights_request_list",
     *     defaults={"_scope": "backend"}
     * )
     */
    public function requestResults(): Response
    {
        $requests = $this->entityManager->getRepository(AgonyzRequest::class)->findAll();

        return new Response(
            $this->twig->render(
                '@AgonyzContaoPageSpeedInsights/request_list.twig.html',
                [
                    'requests' => $requests
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
}
