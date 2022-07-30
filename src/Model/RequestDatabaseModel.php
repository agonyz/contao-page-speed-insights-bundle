<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

namespace Agonyz\ContaoPageSpeedInsightsBundle\Model;

use Contao\Model;

class RequestDatabaseModel extends Model
{
    protected static $strTable = 'tl_agonyz_page_speed_insights';
}
