<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

$GLOBALS['TL_DCA']['tl_news']['palettes']['__selector__'][] = 'addBanner';
$GLOBALS['TL_DCA']['tl_news']['subpalettes']['addBanner'] = 'bannerSRC';

PaletteManipulator::create()
    ->addLegend('banner_legend', 'image_legend', PaletteManipulator::POSITION_AFTER)
    ->addField('addBanner', 'banner_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_news');

$GLOBALS['TL_DCA']['tl_news']['fields']['addBanner'] = [
    'inputType' => 'checkbox',
    'eval' => ['submitOnChange' => true],
    'sql' => ['type' => 'boolean', 'default' => false]
];

$GLOBALS['TL_DCA']['tl_news']['fields']['bannerSRC'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['singleSRC'],
    'inputType' => 'fileTree',
    'eval' => ['fieldType' => 'radio', 'filesOnly' => true, 'extensions' => '%contao.image.valid_extensions%', 'mandatory' => true],
    'sql' => "binary(16) NULL",
];
