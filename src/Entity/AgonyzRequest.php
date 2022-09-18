<?php

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Entity;

use DateTime;
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
     * @ORM\Column(type="integer")
     */
    private int $requestFinalCount;

    /**
     * @ORM\Column(type="integer")
     */
    private int $requestCounter;

    /**
     * @var \DateTime $created
     *
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

    /**
     * @return int
     */
    public function getRequestFinalCount(): int
    {
        return $this->requestFinalCount;
    }

    /**
     * @param int $requestFinalCount
     */
    public function setRequestFinalCount(int $requestFinalCount): void
    {
        $this->requestFinalCount = $requestFinalCount;
    }

    /**
     * @return int
     */
    public function getRequestCounter(): int
    {
        return $this->requestCounter;
    }

    /**
     * @param int $requestCounter
     */
    public function setRequestCounter(int $requestCounter): void
    {
        $this->requestCounter = $requestCounter;
    }

    /**
     * @return DateTime
     */
    public function getCreated(): DateTime
    {
        return $this->created;
    }

    /**
     * @param DateTime $created
     */
    public function setCreated(DateTime $created): void
    {
        $this->created = $created;
    }

    /**
     * @return array
     */
    public function getRequestResults(): array
    {
        return $this->requestResults;
    }

    /**
     * @param array $requestResults
     */
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
