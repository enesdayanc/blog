<?php

namespace AppBundle\Controller;

use AppBundle\Services\PostService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/{page}", defaults={"page" = 1}, name="homepage")
     * @Template("@App/default/index.html.twig")
     */
    public function indexAction($page, Request $request)
    {
        /** @var PostService $postService */
        $postService = $this->get('app.services.post_service');

        return array(
            'pagination' => $postService->getPostPagination($page, 5)
        );
    }

    /**
     * @Route("/post/{slug}", name="show_post")
     * @Template("@App/default/post.html.twig")
     */
    public function showPostAction($slug, Request $request)
    {
        /** @var PostService $postService */
        $postService = $this->get('app.services.post_service');

        $post = $postService->getPostBySlug($slug);

        if (!$post) {
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        return array(
            'post' => $post
        );
    }
}
