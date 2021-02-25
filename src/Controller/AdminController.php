<?php

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    /**
     * 
     * @isGranted("ROLE_EMPLOYE")
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $id = $this->getUser()->getId();

        return $this->render(
            'admin/index.html.twig'
        );
    }
}
