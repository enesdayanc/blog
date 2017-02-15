<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Post;
use AppBundle\Form\CategoryType;
use AppBundle\Form\PostType;
use AppBundle\Services\CategoryService;
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
     * @Route("/{page}", defaults={"page" = 1}, requirements={"page": "\d+"}, name="admin_homepage")
     * @Template("@App/admin/index.html.twig")
     */
    public function indexAction($page, Request $request)
    {
        /** @var PostService $postService */
        $postService = $this->get('app.services.post_service');

        return array(
            'pagination' => $postService->getPostPagination($page)
        );
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

    /**
     * @Route("/categories/{page}", defaults={"page" = 1}, requirements={"page": "\d+"}, name="category_list")
     * @Template("@App/admin/categoryList.html.twig")
     */
    public function categoryListAction($page, Request $request)
    {
        /** @var CategoryService $categoryService */
        $categoryService = $this->get('app.services.category_service');

        return array(
            'pagination' => $categoryService->getCategoryPagination($page)
        );
    }


    /**
     * @Route("/category/create", name="create_category")
     * @Template(":admin:form.html.twig")
     */
    public function createCategoryAction(Request $request)
    {
        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var CategoryService $categoryService */
            $categoryService = $this->get('app.services.category_service');

            $saveResult = $categoryService->saveCategory($category);

            if ($saveResult) {

                $redirectUrl = $this->generateUrl(
                    'edit_category',
                    array(
                        'id' => $category->getId()
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
     * @Route("/category/edit/{id}", name="edit_category")
     * @Template(":admin:form.html.twig")
     */
    public function editCategoryAction($id, Request $request)
    {
        /** @var CategoryService $categoryService */
        $categoryService = $this->get('app.services.category_service');

        $category = $categoryService->getCategoryById($id);

        if (!$category) {
            // todo: log this
            throw $this->createNotFoundException('Unable to find this entity.');
        }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $saveResult = $categoryService->saveCategory($category);

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
     * @Route("/category/delete/{id}", name="delete_category")
     */
    public function deleteCategoryAction($id, Request $request)
    {
        /** @var CategoryService $categoryService */
        $categoryService = $this->get('app.services.category_service');

        $deleteResult = $categoryService->deleteCategoryById($id);

        if ($deleteResult) {
            // todo: show success message
        } else {
            // todo: show error message
        }

        $redirectUrl = $this->generateUrl('category_list');
        return $this->redirect($redirectUrl);
    }
}
