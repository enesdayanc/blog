<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Post;
use AppBundle\Form\PostType;
use AppBundle\Services\PostService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="admin_homepage")
     * @Template("@App/admin/index.html.twig")
     */
    public function indexAction(Request $request)
    {
        return array();
    }

    /**
     * @Route("/post/create", name="create_post")
     * @Template(":admin:form.html.twig")
     */
    public function createPostAction(Request $request)
    {
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var PostService $postService */
            $postService = $this->get('app.services.post_service');

            $saveResult = $postService->savePost($post);

            if ($saveResult) {

                $redirectUrl = $this->generateUrl(
                    'edit_post',
                    array(
                        'id' => $post->getId()
                    )
                );

                return $this->redirect($redirectUrl);
            }
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/post/edit/{id}", name="edit_post")
     * @Template(":admin:form.html.twig")
     */
    public function editPostAction($id, Request $request)
    {
        /** @var PostService $postService */
        $postService = $this->get('app.services.post_service');

        $post = $postService->getPostById($id);

        if (!$post) {
            // todo: log this
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $saveResult = $postService->savePost($post);

            if ($saveResult) {
                // todo: show success message
            } else {
                // todo: show error message
            }
        }


        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Route("/post/delete/{id}", name="delete_post")
     */
    public function deletePostAction($id, Request $request)
    {
        /** @var PostService $postService */
        $postService = $this->get('app.services.post_service');

        $deleteResult = $postService->deletePostById($id);

        if ($deleteResult) {
            // todo: show success message
        } else {
            // todo: show error message
        }

        $redirectUrl = $this->generateUrl('admin_homepage');
        return $this->redirect($redirectUrl);
    }
}
