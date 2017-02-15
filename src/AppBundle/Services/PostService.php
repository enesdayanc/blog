<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 15/02/2017
 * Time: 22:56
 */

namespace AppBundle\Services;

use AppBundle\Entity\Post;
use AppBundle\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Monolog\Logger;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PostService
{
    private $container;


    /**
     * PostService constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }


    /**
     * @param Post $post
     * @return bool
     */
    public function savePost(Post $post)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        try {
            $em->persist($post);
            $em->flush();
        } catch (Exception $e) {
            /** @var Logger $logger */
            $logger = $this->container->get('logger');
            $logger->critical(sprintf('[PostService][savePost] error: %s', $e->getMessage()));
            return false;
        }

        return true;
    }


    /**
     * @param $id
     * @return Post|null
     */
    public function getPostById($id)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository('AppBundle:Post');

        return $postRepository->find($id);
    }

    /**
     * @param $slug
     * @return Post|null
     */
    public function getPostBySlug($slug)
    {
        /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.default_entity_manager');

        /** @var PostRepository $postRepository */
        $postRepository = $em->getRepository('AppBundle:Post');

        return $postRepository->findOneBy(array('slug' => $slug));
    }
}