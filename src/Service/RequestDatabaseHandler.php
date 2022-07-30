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

use Agonyz\ContaoPageSpeedInsightsBundle\Model\RequestDatabaseModel;

class RequestDatabaseHandler
{
    public function createRequestCheck(): void
    {
        $requestChecker = RequestDatabaseModel::findAll();

        if (!$requestChecker) {
            $requestCheck = new RequestDatabaseModel();
            $requestCheck->isRequestRunning = false;
            $requestCheck->save();
        }
    }

    public function isRequestRunning(): bool
    {
        if(!($request = $this->getRequestDatabaseModel())) {
            return false;
        }
        if (!$request->isRequestRunning) {
            return false;
        }
        return true;
    }

    public function setRequestRunning(bool $status): bool
    {
        if(!($request = $this->getRequestDatabaseModel())) {
            return false;
        }
        $request->isRequestRunning = $status;
        $request->save();
        return true;
    }

    private function getRequestDatabaseModel(): ?RequestDatabaseModel
    {
        $model = '';
        if(!($collection = RequestDatabaseModel::findAll())) {
            return null;
        }
        if($collection->count() > 1) {
            return null;
        }

        foreach($collection as $item) {
            $model = $item->current();
        }
        return $model;
    }
}
