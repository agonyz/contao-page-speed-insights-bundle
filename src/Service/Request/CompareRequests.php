<?php

declare(strict_types=1);

namespace Agonyz\ContaoPageSpeedInsightsBundle\Service\Request;

use Agonyz\ContaoPageSpeedInsightsBundle\Entity\AgonyzRequest;

class CompareRequests
{
    public static function compare(AgonyzRequest $request, AgonyzRequest $latestRequest): ?array
    {
        $comparison = [];
        $comparison['request'] = $request;
        $comparison['latestRequest'] = $latestRequest;

        $latestRequestResults = $latestRequest->getRequestResults();
        $requestResults = $request->getRequestResults();

        foreach($latestRequestResults as $domain => $latestRequestResult) {
            if(array_key_exists($domain, $requestResults)) {
                if(isset($latestRequestResult['results']) && isset($requestResults[$domain]['results'])) {
                    foreach($latestRequestResult['results'] as $strategy => $result) {
                        if(isset($latestRequestResults[$domain]['results'][$strategy]) && isset($requestResults[$domain]['results'][$strategy])) {
                            $comparison['comparison'][$domain]['results'][$strategy]['request'] = max($requestResults[$domain]['results'][$strategy]);
                            $comparison['comparison'][$domain]['results'][$strategy]['latestRequest'] = max($latestRequestResults[$domain]['results'][$strategy]);
                            $comparison['comparison'][$domain]['results'][$strategy]['comparison'] = (max($latestRequestResults[$domain]['results'][$strategy]) - max($requestResults[$domain]['results'][$strategy]));
                        }
                    }
                }
            }
        }
        return $comparison;
    }
}
