<?php

/**
 * This file is part of NewsBannerBundle for Contao
 *
 * @package     tdoescher/news-bundle-bundle
 * @author      Torben Döscher <mail@tdoescher.de>
 * @license     LGPL
 * @copyright   tdoescher.de // WEB & IT <https://tdoescher.de>
 */

namespace tdoescher\NewsBannerBundle\Controller\FrontendModule;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Filesystem\FilesystemItem;
use Contao\CoreBundle\Filesystem\FilesystemUtil;
use Contao\CoreBundle\Filesystem\VirtualFilesystem;
use Contao\CoreBundle\Image\Studio\Figure;
use Contao\CoreBundle\Image\Studio\Studio;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\Input;
use Contao\ModuleModel;
use Contao\NewsModel;
use Contao\FilesModel;
use Contao\StringUtil;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

#[AsFrontendModule(category: 'news')]
class NewsBannerController extends AbstractFrontendModuleController
{
    public function __construct(
        private readonly Security $security,
        private readonly VirtualFilesystem $filesStorage,
        private readonly Studio $studio,
        private readonly array $validExtensions,
    ) {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $autoItem = Input::get('auto_item');

        if ($autoItem === null)
        {
            return new Response();
        }

        $news = NewsModel::findPublishedByParentAndIdOrAlias($autoItem, StringUtil::deserialize($model->news_archives));

        if ($news === null && $model->singleSRC === null)
        {
            return new Response();
        }

        $filesystemItems = FilesystemUtil::listContentsFromSerialized($this->filesStorage, [$news->singleSRC, $model->singleSRC])
            ->filter(fn ($item) => \in_array($item->getExtension(true), $this->validExtensions, true));

        $figureBuilder = $this->studio
            ->createFigureBuilder()
            ->setSize($model->imgSize);

        $imageList = array_filter(array_map(
            fn (FilesystemItem $filesystemItem): Figure|null => $figureBuilder
                ->fromStorage($this->filesStorage, $filesystemItem->getPath())
                ->buildIfResourceExists(),
            iterator_to_array($filesystemItems)));

        if (!$imageList) {
            return new Response();
        }

        $template->set('images', $imageList);

        return $template->getResponse();
    }
}
