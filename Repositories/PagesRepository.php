<?php
/**
 * Copyright (c) 2017
 *
 * @package   Majima
 * @author    David Neustadt <kontakt@davidneustadt.de>
 * @copyright 2017 David Neustadt
 * @license   MIT
 */

namespace Plugins\MajimaPages\Repositories;

/**
 * Class PagesRepository
 * @package Plugins\MajimaPages\Repositories
 */
class PagesRepository
{
    /**
     * @var \FluentPDO
     */
    private $qb;

    /**
     * GridRepository constructor.
     * @param \FluentPDO $qb
     */
    public function __construct(\FluentPDO $qb)
    {
        $this->qb = $qb;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getPagesTree($id = 0)
    {
        $children = $this->qb
            ->from('pages')
            ->where('parentID', $id)
            ->fetchAll();

        $categories = array();
        foreach ($children as &$category) {
            $subCategories = $this->getPagesTree($category['id']);
            $category['children'] = $subCategories;
            $categories[] = $category;
        }
        return $categories;
    }

    /**
     * @param $name string
     * @param $parentId int
     */
    public function createPage($name, $parentId)
    {
        $this->qb
            ->insertInto('pages')
            ->values([
                'name' => $name,
                'parentID' => $parentId
            ])
            ->execute();
    }

    /**
     * @param $name string
     * @param $id int
     */
    public function updatePage($name, $id)
    {
        $this->qb
            ->update('pages')
            ->set([
                'name' => $name
            ])
            ->where('id', $id)
            ->execute();
    }

    public function deletePage($id)
    {
        $this->qb
            ->delete('pages')
            ->where('id', $id)
            ->execute();
    }
}