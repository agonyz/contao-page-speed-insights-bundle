<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(type="integer")
     */
    private int $requestFinalCount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $requestCounter;

    /**
     * @ORM\Column(type="datetime")
     */
    private \DateTime $created;

    /**
     * @ORM\Column(type="array")
     */
    private array $requestResults = [];

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private ?bool $successful;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function isRequestRunning(): bool
    {
        return $this->requestRunning;
    }

    public function setRequestRunning(bool $requestRunning): void
    {
        $this->requestRunning = $requestRunning;
    }

    public function getRequestFinalCount(): int
    {
        return $this->requestFinalCount;
    }

    public function setRequestFinalCount(int $requestFinalCount): void
    {
        $this->requestFinalCount = $requestFinalCount;
    }

    public function getRequestCounter(): int
    {
        return $this->requestCounter;
    }

    public function setRequestCounter(int $requestCounter): void
    {
        $this->requestCounter = $requestCounter;
    }

    public function getCreated(): \DateTime
    {
        return $this->created;
    }

    public function setCreated(\DateTime $created): void
    {
        $this->created = $created;
    }

    public function getRequestResults(): array
    {
        return $this->requestResults;
    }

    public function setRequestResults(array $requestResults): void
    {
        $this->requestResults = $requestResults;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): ?bool
    {
        return $this->successful;
    }

    /**
     * @param bool $successful
     */
    public function setSuccessful(?bool $successful): void
    {
        $this->successful = $successful;
    }
}
