<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 16/02/2017
 * Time: 01:21
 */

namespace AppBundle\Services;


use AppBundle\Entity\Category;
use Doctrine\ORM\EntityManager;
use Entity\Repository\CategoryRepository;
use Exception;
use Knp\Component\Pager\Paginator;
use Monolog\Logger;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CategoryService
{
    private $container;


    /**
     * CategoryService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param Category $category
     * @return bool
     */
    public function saveCategory(Category $category)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        try {
            $em->persist($category);
            $em->flush();
        } catch (Exception $e) {
            /** @var Logger $logger */
            $logger = $this->container->get('logger');
            $logger->critical(sprintf('[CategoryService][saveCategory] error: %s', $e->getMessage()));
            return false;
        }

        return true;
    }


    /**
     * @param $id
     * @return Category|null
     */
    public function getCategoryById($id)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $em->getRepository('AppBundle:Category');

        return $categoryRepository->find($id);
    }

    /**
     * @param $slug
     * @return Category|null
     */
    public function getCategoryBySlug($slug)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        /** @var CategoryRepository $categoryRepository */
        $categoryRepository = $em->getRepository('AppBundle:Category');

        return $categoryRepository->findOneBy(array('slug' => $slug));
    }

    /**
     * @param $id
     * @return bool
     */
    public function deleteCategoryById($id)
    {
        $category = $this->getCategoryById($id);

        if (!$category) {
            return false;
        }

        return $this->deleteCategory($category);
    }

    /**
     * @param Category $category
     * @return bool
     */
    public function deleteCategory(Category $category)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        try {
            $em->remove($category);
            $em->flush();
        } catch (Exception $e) {
            /** @var Logger $logger */
            $logger = $this->container->get('logger');
            $logger->critical(sprintf('[CategoryService][deleteCategoryById] error: %s', $e->getMessage()));
            return false;
        }

        return true;
    }

    public function getCategoryPagination($page = 1, $limit = 10)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        $qb = $em->createQueryBuilder();

        $qb->select('post')
            ->from('AppBundle:Category', 'post');

        /** @var Paginator $paginator */
        $paginator = $this->container->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page,
            $limit
        );

        return $pagination;
    }
}