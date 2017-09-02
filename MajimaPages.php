<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Plugins\MajimaPages;

use Majima\PluginBundle\Components\AssetCollection;
use Majima\PluginBundle\Components\ControllerCollection;
use Majima\PluginBundle\Components\RouteCollection;
use Majima\PluginBundle\Components\RouteConfig;
use Majima\PluginBundle\Components\ViewCollection;
use Majima\PluginBundle\PluginAbstract;
use Plugins\MajimaPages\Services\AdminControllerDecorator;
use Plugins\MajimaPages\Services\IndexControllerDecorator;

/**
 * Class MajimaPages
 * @package Plugins\MajimaPages
 */
class MajimaPages extends PluginAbstract
{
    /**
     * @var int
     */
    private $priority = 0;

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    public function install()
    {
        /**
         * @var \FluentPDO $qb
         */
        $qb = $this->container->get('dbal');

        $sql = file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, ['Resources', 'sql', 'install.sql']));

        $pdo = $qb->getPdo();
        $pdo->exec($sql);
    }

    public function build()
    {

    }

    /**
     * @return ControllerCollection
     */
    public function registerControllers()
    {
        return new ControllerCollection([
            'majima.index_controller' => IndexControllerDecorator::class,
            'majima.admin_controller' => AdminControllerDecorator::class,
        ]);
    }

    /**
     * @return RouteCollection
     */
    public function setRoutes()
    {
        $routeCollection = new RouteCollection();
        $routeCollection->addRoute(
            new RouteConfig(
                'index_page',
                '/{p}/',
                'majima.index_controller:indexAction',
                ['p' => '\d+']
            )
        );
        $routeCollection->addRoute(
            new RouteConfig(
                'admin_add_page',
                '/admin/addpage/',
                'majima_pages.majima.admin_controller:addPageAction'
            )
        );
        $routeCollection->addRoute(
            new RouteConfig(
                'admin_update_page',
                '/admin/updatepage/',
                'majima_pages.majima.admin_controller:updatePageAction'
            )
        );
        $routeCollection->addRoute(
            new RouteConfig(
                'admin_delete_page',
                '/admin/updatepage/',
                'majima_pages.majima.admin_controller:deletePageAction'
            )
        );
        return $routeCollection;
    }

    /**
     * @return ViewCollection
     */
    public function setViewResources()
    {
        $viewCollection = new ViewCollection(join(DIRECTORY_SEPARATOR, [__DIR__, 'Resources']));
        $viewCollection->setViews(['views']);
        return $viewCollection;
    }

    /**
     * @return AssetCollection
     */
    public function setCssResources()
    {
        $assetCollection = new AssetCollection(join(DIRECTORY_SEPARATOR, [__DIR__, 'Resources', 'css', 'src', 'frontend']));
        $assetCollection->setFrontendAssets([
            'all.scss',
        ]);
        return $assetCollection;
    }

    /**
     * @return AssetCollection
     */
    public function setJsResources()
    {
        $assetCollection = new AssetCollection(join(DIRECTORY_SEPARATOR, [__DIR__, 'Resources', 'js', 'src', 'backend']));
        $assetCollection->setBackendAssets([
            'majima.pages.js',
        ]);
        return $assetCollection;
    }
}