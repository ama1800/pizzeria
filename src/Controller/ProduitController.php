<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\MenuRepository;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/** 
 * @Route("/produit")
 */
class ProduitController extends AbstractController
{
    /**
     * @isGranted("ROLE_GERANT")
     * @Route("/", name="produit_index", methods={"GET"})
     */
    public function index(ProduitRepository $produitRepository, MenuRepository $menuRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produits' => $produitRepository->findAll(),
            'menus' => $menuRepository->findAll(),
        ]);
    }

    /**
     * @isGranted("ROLE_GERANT")
     * @Route("/new", name="produit_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $produit = new Produit();
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // récupère les images dans le formulaire
            $images = $form->get('images')->getData();

            // boucle sur les images pour générer les liens
            foreach ($images as $image) {
                // génère un nouveau nom de l'image avec l'extension
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // envoie l'image dans le dossier uploads
                $image->move(
                    $this->getParameter('brochures_directory'),
                    $fichier
                );
                // Instance d'objet Image 
                // On crée l'url  dans la base de données (surtout pas le lien complet au cas ou en change de domaine)
                $img = new Image();
                $img->setUrl($fichier);
                $produit->addImage($img);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($produit);
            $entityManager->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/new.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="produit_show", methods={"GET" , "POST"})
     */
    public function show(Request $request, Produit $produit): Response
    {
        // On cree le commentaire
        $commentaire = new Commentaire();
        // On génére le form
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $user = $this->getUser();
        $commentaire->setUser($user);
        $commentaire->setProduit($produit);

        // récupére les données relatives au form dans Request
        $form->handleRequest($request);
        // on recupere le contenu du champ parentid
        $parentId = $form->get('parentId')->getData();
        // traitement du formulaire
        if ($form->isSubmitted() && $form->isValid()) {
            // le commentaire correspendant
            $entityManager = $this->getDoctrine()->getManager();
            // si la valeur du parentId n'est pas vide
            if ($parentId != null) {
                // On récupére le parent de la BDD pour l'inserer dans la requéte de création d'une réponse
                $parent = $entityManager->getRepository(Commentaire::class)->find($parentId);
                // on defint le parent pour repondre à un commentaire existant
                $commentaire->setParent($parent);
                // persiste et sauvgrade ds la BDD
                $entityManager->persist($commentaire);
                $entityManager->flush();
            }
            //si c'est un nouveau commentaire il est déclarer null
            else {
                $commentaire->setParent(null);
                // persiste et sauvgrade ds la BDD
                $entityManager->persist($commentaire);
                $entityManager->flush();
            }


            $this->addFlash('success', 'Votre commentaire à bien été envoyer');
            return $this->redirectToRoute('produit_show', ['id' => $produit->getId()]);
        }

        return $this->render('produit/show.html.twig', [
            'produit' => $produit,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/favoris/ajout/{id}", name="ajout_favoris")
     */
    public function ajoutFavoris(Produit $produit)
    {
        if (!$produit) {
            throw new NotFoundHttpException('Pas de produit trouvé');
        }
        $produit->addFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/favoris/retrait/{id}", name="retrait_favoris")
     */
    public function retraitFavoris(Produit $produit)
    {
        if (!$produit) {
            throw new NotFoundHttpException('Pas de produit trouvé');
        }
        $produit->removeFavori($this->getUser());

        $em = $this->getDoctrine()->getManager();
        $em->persist($produit);
        $em->flush();
        return $this->redirectToRoute('home');
    }

    /**
     * @isGranted("ROLE_GERANT")
     * @Route("/{id}/edit", name="produit_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Produit $produit): Response
    {
        $form = $this->createForm(ProduitType::class, $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // récupère les images dans le formulaire
            $images = $form->get('images')->getData();

            // boucle sur les images pour générer les liens
            foreach ($images as $image) {
                // génère un nouveau nom de l'image avec l'extension
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // envoie l'image dans le dossier uploads
                $image->move(
                    $this->getParameter('brochures_directory'),
                    $fichier
                );
                // Instance d'objet Image 
                // On crée l'url  dans la base de données (surtout pas le lien complet au cas ou en change de domaine)
                $img = new Image();
                $img->setUrl($fichier);
                $produit->addImage($img);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_index');
        }

        return $this->render('produit/edit.html.twig', [
            'produit' => $produit,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @isGranted("ROLE_GERANT")
     * @Route("/{id}", name="produit_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Produit $produit): Response
    {
        if ($this->isCsrfTokenValid('delete' . $produit->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($produit);
            $entityManager->flush();
        }

        return $this->redirectToRoute('produit_index');
    }

    /**
     * @isGranted("ROLE_GERANT")
     * @Route("/supprime/image/{id}", name="produit_delete_image", methods={"DELETE"})
     */
    public function deleteImage(Image $image, Request $request)
    {
        $data = json_decode($request->getContent(), true);

        // vérification de la validation du token
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])) {
            // solicitation de l'url de l'image
            $nom = $image->getUrl();
            // supprission de l'image
            unlink($this->getParameter('brochures_directory') . '/' . $nom);

            // supprime l'entrée de la BDD
            $em = $this->getDoctrine()->getManager();
            $em->remove($image);
            $em->flush();

            // réponse en json
            return new JsonResponse(['success' => 1]);
        } else {
            return new JsonResponse(['error' => 'Token Invalide'], 400);
        }
    }
}
