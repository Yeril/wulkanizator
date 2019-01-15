<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register()
    {
        return $this->render('security/register.html.twig');
    }

    /**
     * @Route("/logout", name="app_logout")
     * @throws \Exception
     */
    public function logout()
    {
        throw new \Exception("Logout failed!");
    }
}
