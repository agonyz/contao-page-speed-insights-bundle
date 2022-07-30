<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

use Agonyz\ContaoPageSpeedInsightsBundle\Model\RequestDatabaseModel;

$GLOBALS['TL_MODELS']['tl_agonyz_page_speed_insights'] = RequestDatabaseModel::class;
