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

use Majima\Controller\AdminController;
use Plugins\MajimaPages\Repositories\PagesRepository;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class AdminControllerDecorator
 * @package Plugins\MajimaGrid\Services
 */
class AdminControllerDecorator extends AdminController
{
    /**
     * @var AdminController
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
        $this->pagesRepository = new PagesRepository($this->container->get('dbal'));
    }

    /**
     * @return PagesRepository
     */
    public function getPagesRepository()
    {
        return $this->pagesRepository;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function addPageAction(Request $request)
    {
        $name = $request->get('name');
        $parentId = $request->get('parentId');

        $this->pagesRepository->createPage($name, $parentId);

        return new Response(
            json_encode(['success' => true]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function updatePageAction(Request $request)
    {
        $name = $request->get('name');
        $id = $request->get('id');

        $this->pagesRepository->updatePage($name, $id);

        return new Response(
            json_encode(['success' => true]),
            200,
            ['Content-Type' => 'application/json']
        );
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function deletePageAction(Request $request)
    {
        $id = $request->get('id');

        $this->pagesRepository->deletePage($id);

        return new Response(
            json_encode(['success' => true]),
            200,
            ['Content-Type' => 'application/json']
        );
    }
}