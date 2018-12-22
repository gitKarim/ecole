<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AboutController extends AbstractController
{
    /**
     * @Route("/about", name="page_about")
     */
    public function index()
    {
        return $this->render('home/about.html.twig', [
            'controller_name' => 'AboutController',
        ]);
    }
}
