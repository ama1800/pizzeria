<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/categorie")
 */
class CategorieController extends AbstractController
{
    /**
     * @Route("/", name="categorie_index", methods={"GET"})
     */
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    /**
     * @Route("/pizzas", name="pizzas", methods={"GET"})
     */
    public function pizza(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            // dd($categorie);
            if ($categorie->getCategorieLibelle() == 'PIZZAS') {
                return $this->render('categorie/pizzas.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }

    /**
     * @Route("/boissons", name="boissons", methods={"GET"})
     */
    public function boisson(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            if ($categorie->getCategorieLibelle() == 'BOISSONS') {
                return $this->render('categorie/boissons.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }
    /**   
     * @Route("/desserts", name="desserts", methods={"GET"})
     */
    public function dessert(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            if ($categorie->getCategorieLibelle() == 'DESSERTS') {
                return $this->render('categorie/desserts.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }

    /**   
     * @Route("/tacos", name="tacos", methods={"GET"})
     */
    public function tacos(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            if ($categorie->getCategorieLibelle() == 'TACOS') {
                return $this->render('categorie/tacos.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }

    /**   
     * @Route("/salades", name="salades", methods={"GET"})
     */
    public function salades(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            if ($categorie->getCategorieLibelle() == 'SALADES') {
                return $this->render('categorie/salades.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }
    /**   
     * @Route("/sauces", name="sauces", methods={"GET"})
     */
    public function sauces(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            if ($categorie->getCategorieLibelle() == 'SAUCES') {
                return $this->render('categorie/sauces.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }

    /**   
     * @Route("/extras", name="extras", methods={"GET"})
     */
    public function extras(CategorieRepository $categorieRepository): Response
    {
        $categories = $categorieRepository->findAll();
        foreach ($categories as $categorie) {
            $produits = $categorie->getProduits();
            if ($categorie->getCategorieLibelle() == 'EXTRAS') {
                return $this->render('categorie/extras.html.twig', [
                    'produits' => $produits,
                ]);
            }
        }
    }

    /**
     * @Route("/new", name="categorie_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categorie = new Categorie();
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categorie);
            $entityManager->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/new.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_show", methods={"GET"})
     */
    public function show(Categorie $categorie): Response
    {
        return $this->render('categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="categorie_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Categorie $categorie): Response
    {
        $form = $this->createForm(CategorieType::class, $categorie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('categorie_index');
        }

        return $this->render('categorie/edit.html.twig', [
            'categorie' => $categorie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="categorie_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Categorie $categorie): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categorie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categorie);
            $entityManager->flush();
        }

        return $this->redirectToRoute('categorie_index');
    }
}
