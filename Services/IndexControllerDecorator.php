<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Plugins\MajimaPages\Services;

use Majima\Controller\IndexController;
use Plugins\MajimaPages\Repositories\PagesRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class IndexControllerDecorator
 * @package Plugins\MajimaPages\Services
 */
class IndexControllerDecorator extends IndexController
{
    /**
     * @var IndexController
     */
    private $controller;

    /**
     * @var PagesRepository
     */
    private $pagesRepository;

    /**
     * IndexControllerDecorator constructor.
     * @param $controller
     * @param Container $container
     * @param RouterInterface $router
     */
    public function __construct($controller, Container $container, RouterInterface $router)
    {
        parent::__construct($container, $router);

        $this->controller = $controller;
        $this->pagesRepository = new PagesRepository($this->dbal);
    }

    /**
     * @return PagesRepository
     */
    public function getPagesRepository()
    {
        return $this->pagesRepository;
    }

    public function indexAction(Request $request)
    {
        $this->controller->indexAction($request);

        $pageId = (int)$request->get('p', 0);
        $pages = $this->getPagesRepository()->getPagesTree();

        $this->assign(
            [
                'pageId' => $pageId,
                'pages' => $pages,
            ]
        );
    }
}