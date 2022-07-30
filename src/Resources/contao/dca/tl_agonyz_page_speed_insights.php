<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

use Contao\DC_Table;

const AGONYZ_PAGE_SPEED_INSIGHTS_TABLE = 'tl_agonyz_page_speed_insights';

$GLOBALS['TL_DCA'][AGONYZ_PAGE_SPEED_INSIGHTS_TABLE]['config'] = [
    'dataContainer' => DC_Table::class,
    'enableVersioning' => false,
    'sql' => [
        'keys' => [
            'id' => 'primary',
        ],
    ],
];

$GLOBALS['TL_DCA'][AGONYZ_PAGE_SPEED_INSIGHTS_TABLE]['fields']['id'] = [
    'sql' => 'int(10) unsigned NOT NULL auto_increment',
];

$GLOBALS['TL_DCA'][AGONYZ_PAGE_SPEED_INSIGHTS_TABLE]['fields']['isRequestRunning'] = [
    'sql' => ['type' => 'string', 'length' => 1, 'fixed' => true, 'default' => 0],
];
