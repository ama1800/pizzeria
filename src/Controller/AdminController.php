<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Repository\MenuRepository;
use App\Repository\UserRepository;
use App\Repository\ProduitRepository;
use App\Repository\CommandeRepository;
use App\Repository\IngredientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
