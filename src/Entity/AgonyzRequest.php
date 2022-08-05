<?php

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Agonyz\ContaoPageSpeedInsightsBundle\Repository\AgonyzRequestRepository;

/**
 * @ORM\Entity(repositoryClass="Agonyz\ContaoPageSpeedInsightsBundle\Repository\AgonyzRequestRepository")
 * @ORM\Table(name="tl_agonyz_page_speed_insights")
 */
class AgonyzRequest
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $requestRunning;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return bool
     */
    public function isRequestRunning(): bool
    {
        return $this->requestRunning;
    }

    /**
     * @param bool $requestRunning
     */
    public function setRequestRunning(bool $requestRunning): void
    {
        $this->requestRunning = $requestRunning;
    }
}
