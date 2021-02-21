<?php

namespace App\Controller;

use Dompdf\Dompdf;
use App\Repository\FactureRepository;
use App\Repository\CommandeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
    /**
     * @isGranted("ROLE_ADMIN") 
     * @Route("/", name="facture")
     */
    public function index(FactureRepository $factureRepository): Response
    {

        return $this->render('facture/index.html.twig', [
            'factures' => $factureRepository->findBy([], ['createdAt' => 'DESC']),
        ]);
    }

    /**
     * @isGranted("ROLE_USER") 
     * @Route("/userFactures", name="user_factures")
     */
    public function userFactures(Request $request, PaginatorInterface $paginatorInterface, FactureRepository $factureRepository, CommandeRepository $commandeRepository): Response
    {
        $userId = $this->getUser();
        $lastUserCommande = $commandeRepository->findOneBy(['user' => $userId], ['creatAt' => 'DESC']);
        $userCommandesIds = $commandeRepository->findBy(['user' => $userId], ['creatAt' => 'DESC']);
        $lastFacture = $factureRepository->findOneBy(['commande' => $lastUserCommande], ['createdAt' => 'DESC']);
        $factures = $factureRepository->findBy(['commande' => $userCommandesIds], ['createdAt' => 'DESC']);
        $pagination = $paginatorInterface->paginate(
            $factures, // on passe les donnees
            $request->query->getInt('page', 1), // numero de la page en cours, la page 1 par defaut
            5, //nombre d'elements par page.
        );

        return $this->render('facture/factures.html.twig', [
            'lastCommande' => $lastUserCommande,
            'commandes' => $userCommandesIds,
            'lastFacture' => $lastFacture,
            'factures' => $factures,
            'pagination' => $pagination

        ]);
    }

    /**
     * @isGranted("ROLE_USER") 
     * @Route("/show/{numero}", name="show_facture")
     */
    public function showFacture(Request $request, FactureRepository $factureRepository, CommandeRepository $commandeRepository): Response
    {
        $numero = $request->get('numero');
        $facture = $factureRepository->findOneBy(['numero' => $numero]);
        $commandeId = $facture->getCommande()->getId();
        $commande = $commandeRepository->findOneBy(['id' => $commandeId]);

        return $this->render('facture/pdf.html.twig', [
            'facture' => $facture,
            'commande' => $commande
        ]);
    }

    /**
     * @isGranted("ROLE_USER") 
     * @Route("/pdf/{numero}", name="pdf_facture")
     */
    public function generatePdf(Request $request, FactureRepository $factureRepository, CommandeRepository $commandeRepository): Response
    {
        $numero = $request->get('numero');
        $facture = $factureRepository->findOneBy(['numero' => $numero]);
        $commandeId = $facture->getCommande()->getId();
        $commande = $commandeRepository->findOneBy(['id' => $commandeId]);

        // instantiate and use the dompdf class
        $dompdf = new Dompdf();
        $html = $this->renderView('facture/pdf.html.twig', [
            'facture' => $facture,
            'commande' => $commande
        ]);
        // $html .= 
        // $html .= ;
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
        $dompdf->setPaper('A4', 'landscape');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser
        $dompdf->stream();

        return new Response('succes...');
    }
}
