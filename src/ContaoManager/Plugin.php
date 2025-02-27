<?php

/**
 * This file is part of NewsBannerBundle for Contao
 *
 * @package     tdoescher/news-banner-bundle
 * @author      Torben Döscher <mail@tdoescher.de>
 * @license     LGPL
 * @copyright   tdoescher.de // WEB & IT <https://tdoescher.de>
 */

namespace tdoescher\NewsBannerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\NewsBundle\ContaoNewsBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use tdoescher\NewsBannerBundle\NewsBannerBundle;

class Plugin implements BundlePluginInterface
{
  public function getBundles(ParserInterface $parser): array
  {
    return [
      BundleConfig::create(NewsBannerBundle::class)
        ->setLoadAfter([ContaoNewsBundle::class])
    ];
  }
}
