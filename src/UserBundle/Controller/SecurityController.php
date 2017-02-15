<?php
/**
 * Created by PhpStorm.
 * User: enesdayanc
 * Date: 16/02/2017
 * Time: 00:33
 */

namespace UserBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends \FOS\UserBundle\Controller\SecurityController
{
    public function loginAction(Request $request)
    {
        return parent::loginAction($request);
    }


    protected function renderLogin(array $data)
    {
        return $this->render('@User/login.html.twig', $data);
    }
}