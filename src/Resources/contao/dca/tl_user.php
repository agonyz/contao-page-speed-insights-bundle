<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_user']['fields']['agonyz_page_speed_insights'] = [
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => ['multiple' => true],
    'options' => [
        'agonyz_page_speed_insights' => 'Page Speed Insights'
    ],
    'sql' => ['type' => 'blob', 'notnull' => false],
];

PaletteManipulator::create()
    ->addLegend('agonyz_page_speed_insights_legend', 'account_legend', PaletteManipulator::POSITION_BEFORE)
    ->addField('agonyz_page_speed_insights', 'agonyz_page_speed_insights_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('extend', 'tl_user')
    ->applyToPalette('custom', 'tl_user')
;
