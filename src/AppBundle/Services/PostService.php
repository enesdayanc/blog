<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 15/02/2017
 * Time: 22:56
 */

namespace AppBundle\Services;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityManager;
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
            return false;
        }

        return true;
    }

}