<?php

namespace App\Controller;

use App\Entity\ProduitsOnMenu;
use App\Form\ProduitsOnMenuType;
use App\Repository\ProduitsOnMenuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/produits/on/menu")
 */
class ProduitsOnMenuController extends AbstractController
{
    /**
     * @Route("/", name="produits_on_menu_index", methods={"GET"})
     */
    public function index(ProduitsOnMenuRepository $produitsOnMenuRepository): Response
    {
        return $this->render('produits_on_menu/index.html.twig', [
            'produits_on_menus' => $produitsOnMenuRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="produits_on_menu_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produitsOnMenu = new ProduitsOnMenu();
        $form = $this->createForm(ProduitsOnMenuType::class, $produitsOnMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produitsOnMenu);
            $entityManager->flush();

            return $this->redirectToRoute('produits_on_menu_index');
        }

        return $this->render('produits_on_menu/new.html.twig', [
            'produits_on_menu' => $produitsOnMenu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produits_on_menu_show", methods={"GET"})
     */
    public function show(ProduitsOnMenu $produitsOnMenu): Response
    {
        return $this->render('produits_on_menu/show.html.twig', [
            'produits_on_menu' => $produitsOnMenu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="produits_on_menu_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProduitsOnMenu $produitsOnMenu): Response
    {
        $form = $this->createForm(ProduitsOnMenuType::class, $produitsOnMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produits_on_menu_index');
        }

        return $this->render('produits_on_menu/edit.html.twig', [
            'produits_on_menu' => $produitsOnMenu,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produits_on_menu_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProduitsOnMenu $produitsOnMenu): Response
    {
        if ($this->isCsrfTokenValid('delete'.$produitsOnMenu->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produitsOnMenu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produits_on_menu_index');
    }
}
