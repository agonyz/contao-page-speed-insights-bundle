<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

$GLOBALS['TL_PERMISSIONS'][] = 'agonyz_page_speed_insights';

if (TL_MODE === 'BE') {
    $GLOBALS['TL_CSS'][] = 'bundles/agonyzcontaopagespeedinsights/style/backend.css';
}
