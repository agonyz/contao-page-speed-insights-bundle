<?php

declare(strict_types=1);

/*
 * This file is part of agonyz/contao-page-speed-insights-bundle.
 *
 * (c) 2022 agonyz
 *
 * @license LGPL-3.0-or-later
 */

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_page']['fields']['activatePageSpeedInsights'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_page']['activatePageSpeedInsights'],
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50'],
    'sql' => ['type' => 'string', 'length' => 1, 'fixed' => true, 'default' => 0],
];

PaletteManipulator::create()
    ->addLegend('agonyz_page_speed_insights_legend:hide', 'website_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('activatePageSpeedInsights', 'agonyz_page_speed_insights_legend:hide', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('rootfallback', 'tl_page')
;
