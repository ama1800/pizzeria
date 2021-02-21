<?php

namespace App\Controller;

use DateTime;
use App\Entity\Facture;
use App\Entity\Commande;
use App\Form\ProfileType;
use App\Entity\CommandeProducts;
use App\Service\Panier\MenuService;
use App\Repository\FactureRepository;
use App\Service\Panier\PanierService;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class CommandeController extends AbstractController
{
    /**
     * **Le Panier
     * @Route("/commande", name="commande")
     */
    public function panier(PanierService $panier, CommandeRepository $commandeRepository): Response
    {

        // $panier->destroy();

        return $this->render('commande/panier.html.twig', [
            'commandes' => $commandeRepository->findAll(['creatAt' => 'DESC']),
            'items' => $panier->fullPanier(),
            'total' => $panier->getTotal(),
            'nb' => $panier->nbProduits()
        ]);
    }


    /**
     * **Liste de commandes
     * @Route("/commande/liste", name="commande_list")
     */
    public function liste(Request $request, CommandeRepository $commandeRepository, PaginatorInterface $paginatorInterface): Response
    {

        $donnees = $commandeRepository->findBy([], ['creatAt' => 'DESC']);
        $commandes = $paginatorInterface->paginate(
            $donnees, // on passe les donnees
            $request->query->getInt('page', 1), // numero de la page en cours, la page 1 par defaut
            10, //nombre d'elements par page.
        );
        return $this->render('commande/list.html.twig', [
            'commandes' => $commandes,
        ]);
    }

    /**
     * **La commande détails
     * @Route("/commande/{id}/show", name="commande_show", methods={"GET"})
     */
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }


    /**
     * **confirmation des coordonnes
     * @Route("/commande/coordonnes", name="commande_coordonnes")
     */
    public function coordonnes(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if ($user) {
            //  id d'user dans la session
            $id = $this->getUser()->getId();
            //  objet user dans la session
            $user = $this->getUser();
            //  form modification email et etat civile adresse
            $form = $this->createForm(ProfileType::class, $user);

            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $entityManager->persist($user);
                $entityManager->flush();
                // message de succee
                $this->addFlash('message', 'Profile à jour. Merci');
                // redirection vers la page du payement
                return $this->redirectToRoute('commande_payement');
            }
            return $this->render('commande/coordonnes.html.twig', [
                'user' => $user,
                'form' => $form->createView(),
            ]);
        } else {
            $this->addFlash('danger', 'Vous avez un compte? Connectez-vous si non insrivez vous ça se fait en moins de 2 minutes. Merci');
            return $this->redirectToRoute('app_login');
        }
    }

    /**
     * **Déclaration de l'intention de payement.
     * @Route("/payement", name="commande_payement", methods={"GET","POST"})
     */
    public function payementIntention(Request $request, PanierService $panierService): Response
    {
        $panier = $panierService->fullPanier();
        // dd($panier);
        $total = $panierService->getTotal()[0];
        $user = $this->getUser();
        if ($user) {
            if ($panierService->getTotal()[1] > 0 || empty($panier)) {
                if (empty($panier)) {
                    $this->addFlash('info', 'Votre panier est vide ! Merci d\'y ajoutez des produits!');
                } else {
                    $this->addFlash('info', 'Il vous faut encore ' . $panierService->getTotal()[1] . '€ pour atteidre le minumun à commandeProductsr.');
                }
                return $this->redirectToRoute('pizzas');
            }
            // Nous instancions Stripe en indiquand la clé privée, pour prouver que nous sommes bien à l'origine de cette demande
            \Stripe\Stripe::setApiKey('sk_test_51IGWJHI5qy2R9q2QSynCB1vqnPS5hVEcWTvEeVV8ghFzvlwAPVYPWex7hCj2c27BLahy8D2w4HqzVjAFVG4yikNl00mapehpM6');

            // Nous créons l'intention de paiement et stockons la réponse dans la variable $intent
            $intent = \Stripe\PaymentIntent::create([
                'amount' => $total * 100, // Le total doit être transmis en centimes
                'currency' => 'eur',
                // Verify your integration in this guide by including this parameter
                'metadata' => [
                    'userId' => $user->getId(),
                    'integration_check' => 'accept_a_payment'
                ],
            ]);
            // la clé secret à passer au button da validation
            $secret = $intent->client_secret;
        } else {
            $this->addFlash('danger', 'Vous avez un compte? Connectez-vous si non insrivez vous ça se fait en moins de 2 minutes. Merci');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('commande/payement.html.twig', [
            'intent' => $secret,
            'panier' => $panier,
            'total' => $total
        ]);
    }

    /**
     * **Sauvgarde de la commande dans la BDD
     * @Route("/commande/store", name="store_commande", methods={"POST","GET"})
     */
    public function storeCommande(PanierService $panierService, Request $request, SessionInterface $sessionInterface)
    {

        $panier = $panierService->fullPanier();
        $data = $request->getContent();
        $user = $this->getUser();
        $params = json_decode($data, true);
        if (!empty($data)) { // 2nd param to get as array
            if ($params['paymentIntent']['status'] === 'succeeded') {
                $dateCommande = (new DateTime())->setTimestamp($params['paymentIntent']['created']);
                $commande = new Commande();
                $nom = $this->getUser()->getNom();
                $prenom = $this->getUser()->getPrenom();
                $email = $this->getUser()->getEmail();
                $adresse = $this->getUser()->getAdresse() . ' ' . $this->getUser()->getCp() . ' ' . $this->getUser()->getVille();
                $telephone = $this->getUser()->getTelephone();
                // on fournis donnée necéssaire pour enregistrer la commande dans la base de données
                $commande->setNom($nom);
                $commande->setPrenom($prenom);
                $commande->setEmail($email);
                $commande->setTelephone($telephone);
                $commande->setUser($user);
                $commande->setAdresseLivraison($adresse);
                $commande->setMontantTtc($params['paymentIntent']['amount'] / 100);
                $commande->setPayementId($params['paymentIntent']['id']);
                $commande->setCreatAt($dateCommande);
                // On sauvgarde la commande
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($commande);
                $entityManager->flush();

                foreach ($panier as $array) {
                    // Entity CommandeProducts
                    $commandeProducts = new CommandeProducts();
                    $produit = $array['produit'];
                    $qte = $array['quantite'];
                    // on fournis donnée necéssaire pour enregistrer la commande avec ses produits dans la base de données
                    $commandeProducts->setProduit($produit);
                    $commandeProducts->setQte($qte);
                    $commandeProducts->setCommande($commande);

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($commandeProducts);
                    $entityManager->flush();
                }
                $facture = new Facture();
                $num = 'F' . round(microtime(true) * 1000) . "t" . $commande->getId();
                $facture->setNumero($num);
                $facture->setCommande($commande);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($facture);
                $entityManager->flush();

                $session = $sessionInterface->set('facture',  [
                    'numero' => $facture->getNumero(),
                    'temps' => $facture->getCreatedAt(),
                    'commande' => $facture->getCommande(),
                ]);

                return new Response(json_encode(
                    [
                        'numero' => $facture->getNumero(),
                        'temps' => $facture->getCreatedAt(),
                        'commande' => $facture->getCommande(),
                    ]
                ));
            }
        } else {
            new Response(json_encode(['fail' => 'Payement à echoué.']));
        }
    }


    /**
     * **La page du payement success
     * @Route("/commande/success", name="success_commande")
     */
    public function successCommande(PanierService $panierService): Response
    {
        return $this->render('commande/success.html.twig', [
            'panier' => $panierService->fullPanier(),
            'total' => $panierService->getTotal()[0],
            'destroy' => $panierService->destroy(),
        ]);
    }

    /**
     * **Ajouter un seul produit au panier
     * @Route("/panier/add/{id}", name="panier_add")
     */
    public function add($id, PanierService $panier): Response //function pour ajouter un produit au le panier
    {
        $panier->add($id);
        $this->addFlash('success', 'Le Produit a été ajouter au panier.');

        return new Response(json_encode(['success' => 'Produits dans le panier.']));
    }

    /**
     * **Supprimer un produit du panier
     * @Route("/panier/{id}/moins", name="panier_moins")
     */
    public function moins($id, PanierService $panierService) //function pour supprimer un produit du le panier
    {
        // On fait appel à la methode moins du Service App\Service\Panier\PanierService 
        // pour decrementer ou supprime le produit
        $panier = $panierService->fullPanier();
        foreach ($panier as $k => $v) {
            // dd($v['produit']->getId(), $panier);
            if ($v['produit']->getId() == $id) {
                $panierService->moins($id);
                return $this->addFlash('danger', 'Le Produit a été enlevé du panier');
            } else {
                // au cas ou l'user à appyer plusieur fois pour supprimer un produit qui est déjà supprimer
                return  $this->addFlash('info', 'Produit introuvable!');
            }
        }
    }


    /**
     * **Supprimer tous les produits avec le meme id du panier
     * @Route("/panier/{id}/remove", name="panier_remove")
     */
    public function remove($id, PanierService $panier)
    {
        // On fait appel à la methode remove du Service App\Service\Panier\PanierService 
        // pour supprimer des produits avec meme id du panier

        $panier->remove($id);

        return $this->addFlash('danger', 'Produit supprimer du panier');
    }


    /**
     * **Ajouter les produit en quantité avec l'ajax
     * @Route("/panier/{id}/up", name="panier_up")
     */
    public function update($id, Request $request, PanierService $panier): Response
    {
        // $panier->destroy();
        // le contenu de l'ajax
        $data = $request->getContent();
        // contenu converti en json
        $params = json_decode($data, true);
        // la quantité selectionnée et envoyer en ajax
        $qte = intval($params['qte']);
        // id de l'url à incrementer
        $id = $request->get('id');
        $panier->panierUp($id, $qte);
        $this->addFlash('success', 'Produit ajouter au panier');
        return new Response(json_encode([
            'success' => 'Produits ont été ajouter.',
            'items' => $panier->fullPanier(),
            'total' => $panier->getTotal(),
            'nb' => $panier->nbProduits()
        ]));
    }
}
