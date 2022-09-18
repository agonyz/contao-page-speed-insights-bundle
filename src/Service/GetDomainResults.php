<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Service;

use Agonyz\ContaoPageSpeedInsightsBundle\Entity\AgonyzRequest;
use Agonyz\ContaoPageSpeedInsightsBundle\Repository\AgonyzRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Response;
use Agonyz\ContaoPageSpeedInsightsBundle\Service\RequestDatabaseHandler;

class GetDomainResults
{
    public const PAGE_SPEED_INSIGHTS_URL = 'https://pagespeed.web.dev/report';
    public const PAGE_SPEED_INSIGHTS_API_URL = 'https://www.googleapis.com/pagespeedonline/v5/runPagespeed';

    public const PAGE_SPEED_INSIGHTS_KEY_SELF_CHECK_URL = 'selfCheckUrl';
    public const PAGE_SPEED_INSIGHTS_KEY_FINAL_URL = 'finalUrl';
    public const PAGE_SPEED_INSIGHTS_KEY_DEFAULT_URL = 'defaultUrl';
    public const PAGE_SPEED_INSIGHTS_KEY_LIGHTHOUSE_RESULT = 'lighthouseResult';
    public const PAGE_SPEED_INSIGHTS_KEY_DESKTOP = 'desktop';
    public const PAGE_SPEED_INSIGHTS_KEY_MOBILE = 'mobile';
    public const DOMAIN_KEY_RESULT = 'results';

    private GetDomainUrls $getDomainUrls;
    private string $apiKey;
    private int $requestRetries;
    private int $requestCounter = 0;
    private Client $client;
    private array $strategies = ['mobile', 'desktop'];
    private string $usedStrategy = self::PAGE_SPEED_INSIGHTS_KEY_DESKTOP;
    private array $domainResults = [];
    private int $poolRequestConcurrency;
    private AgonyzRequest $request;

    public function __construct(GetDomainUrls $getDomainUrls, string $apiKey, int $requestRetries, int $poolRequestConcurrency, EntityManagerInterface $entityManager)
    {
        $this->getDomainUrls = $getDomainUrls;
        $this->apiKey = $apiKey;
        $this->requestRetries = $requestRetries;
        $this->client = new Client();
        $this->poolRequestConcurrency = $poolRequestConcurrency;
        $this->entityManager = $entityManager;
    }

    public function getDomainResults(AgonyzRequest $request): ?array
    {
        if (!$this->apiKey) {
            return null;
        }

        if (!($domainUrls = $this->getDomainUrls->getDomainUrls())) {
            return null;
        }

        $this->request = $request;
        $retries = $this->requestRetries * 2; // retries for each strategy
        $this->request->setRequestFinalCount(count($domainUrls) * $retries);
        $this->entityManager->persist($this->request);
        $this->entityManager->flush();

        for ($i = 0; $i < $retries; ++$i) {
            $this->createRequestPool($domainUrls);
            ++$this->requestCounter;

            if (self::PAGE_SPEED_INSIGHTS_KEY_DESKTOP === $this->usedStrategy) {
                $this->usedStrategy = self::PAGE_SPEED_INSIGHTS_KEY_MOBILE;
            } else {
                $this->usedStrategy = self::PAGE_SPEED_INSIGHTS_KEY_DESKTOP;
            }
        }

        return $this->domainResults;
    }

    private function createRequestPool(array $domainUrls): void
    {
        $requestsGenerator = function (array $domainUrls) {
            foreach ($domainUrls as $url) {
                yield $url => function () use ($url) {
                    if (isset($this->domainResults[$url][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL])) {
                        $url = $this->domainResults[$url][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL];
                    }

                    return $this->client->getAsync(sprintf(
                        '%s?url=%s&strategy=%s&key=%s',
                        self::PAGE_SPEED_INSIGHTS_API_URL,
                        $url,
                        $this->usedStrategy,
                        $this->apiKey
                    ));
                };
            }
        };

        $pool = new Pool($this->client, $requestsGenerator($domainUrls), [
            'concurrency' => $this->poolRequestConcurrency,
            'fulfilled' => function (Response $response, $index): void {
                $json = json_decode($response->getBody()->getContents(), true);

                // set optimized final url for better results
                if (!isset($this->domainResults[$index][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL]) && $json[self::PAGE_SPEED_INSIGHTS_KEY_LIGHTHOUSE_RESULT][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL]) {
                    $this->domainResults[$index][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL] = $json[self::PAGE_SPEED_INSIGHTS_KEY_LIGHTHOUSE_RESULT][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL];
                }

                if (!isset($this->domainResults[$index][self::PAGE_SPEED_INSIGHTS_KEY_SELF_CHECK_URL]) && $json[self::PAGE_SPEED_INSIGHTS_KEY_LIGHTHOUSE_RESULT][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL]) {
                    $this->domainResults[$index][self::PAGE_SPEED_INSIGHTS_KEY_SELF_CHECK_URL] = sprintf('%s?url=%s', self::PAGE_SPEED_INSIGHTS_URL, $json[self::PAGE_SPEED_INSIGHTS_KEY_LIGHTHOUSE_RESULT][self::PAGE_SPEED_INSIGHTS_KEY_FINAL_URL]);
                }

                if (!isset($this->domainResults[$index][self::PAGE_SPEED_INSIGHTS_KEY_DEFAULT_URL])) {
                    $this->domainResults[$index][self::PAGE_SPEED_INSIGHTS_KEY_DEFAULT_URL] = $index;
                }
                $this->domainResults[$index][self::DOMAIN_KEY_RESULT][$this->usedStrategy][$this->requestCounter] = $json[self::PAGE_SPEED_INSIGHTS_KEY_LIGHTHOUSE_RESULT]['categories']['performance']['score'];
                $this->request->setRequestCounter($this->request->getRequestCounter() + 1);
                $this->entityManager->persist($this->request);
                $this->entityManager->flush();
            },
            'rejected' => static function (RequestException $reason, $index): void {
                // failed requests are skipped for now
                $this->request->setRequestCounter($this->request->getRequestCounter() + 1);
                $this->entityManager->persist($this->request);
                $this->entityManager->flush();
            },
        ]);
        $promise = $pool->promise();
        $promise->wait();
    }
}
