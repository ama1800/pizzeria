<?php

namespace App\Service\Panier;

use App\Repository\ProduitRepository;
//SessionInterface permet de recupérer ce qui est stocké à la session 
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{

    protected $session;
    protected $produitRepository;

    public function __construct(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $this->session = $session;
        $this->produitRepository = $produitRepository;
    }

    public function fullPanier(): array
    {
        // on recupére le panier de la session si il existe, si non on récupére tableau vide
        $panier = $this->session->get('panier', []);

        //  on cree un tableau pour y stock  les données des produits ajouter au panier (les objets)
        $panierData = [];

        foreach ($panier as $id => $qte) { //$id: id du produit et $qte: qantite selectionner
            $panierData[] = [
                'produit' => $this->produitRepository->find($id),
                'quantite' => $qte
            ];
        }
        return $panierData;
    }
    public function panierUp(int $id, $qte)
    {
        //  le panier
        $panier = $this->session->get('panier', []);
        // en iterer sur le contenu du panier
        // dd($panier);
        if (!empty($panier[$id])) {

            // Si y existe dejà des produits on en rajoute, on increment le panier
            $panier[$id] += $qte;
        } else {
            // si panier ne contient pas de produit on y ajout le premier
            $panier[$id] = $qte;
        }
        // On actualise le panier pour y ajouter les produits
        $this->session->set('panier', $panier);
    }


    public function add(int $id)
    {
        // On recupére le panier de la session
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            // Si y existe dejà des produits on en rajoute, on increment le panier
            $panier[$id]++;
        } else {
            // si panier ne contient pas de produit on y ajout le premier
            $panier[$id] = 1;
        }
        // On actualise le panier pour y ajouter les produits a chaque fois on fait appel à add()
        $this->session->set('panier', $panier);
    }

    public function moins(int $id)
    {
        // On recupére le panier de la session
        $panier = $this->session->get('panier', []);

        // Si le nombre de produits et plus que un on supprime un et en décremente le panier
        if (($panier[$id] > 1)) {
            $panier[$id]--;

            // si panier contient un seul  produit on le supprime
        } elseif ($panier[$id] == 1) {
            unset($panier[$id]);
        }
        // On actualise le panier pour decrementer les produits a chaque fois on appel moins()
        $this->session->set('panier', $panier);
    }

    public function remove(int $id)
    {

        // On recupére le panier de la session
        $panier = $this->session->get('panier', []);

        // Si le produit existe
        if (!empty($panier[$id])) {

            // suprime le produit en question
            unset($panier[$id]);
        }

        // On actualise le panier aprés supprission
        $this->session->set('panier', $panier);
    }

    public function getTotal(): array
    {
        // pour calculer le total des prix des produit dans le panier
        $total = 0;
        $reste = 0;

        foreach ($this->FullPanier() as $produit) {

            // 1erement on calcul le prix du produit fois la quantite pour obtenir le total
            $total +=  $produit['produit']->getProduitPrix() * $produit['quantite'];

            // $reste représente le minimum à commander il faut l'atteindre pour avoir droit à commander
            $reste = 15 - $total;
        }
        return [$total, $reste];
    }
    public function nbProduits(): int
    {
        // Calcul de nombre de produit dans le panier
        $nb = 0;

        // on recupére le panier de la session si il est vide on récupére tableau vide
        $panier = $this->session->get('panier', []);
        foreach ($this->FullPanier() as $produit) {
            // Si le panier est vide
            if ($panier == []) {
                return $nb = 0;
            }
            // Incrementation des produits dans $nb pour les compter.
            $nb +=  $produit['quantite'];
        }

        return $nb;
    }

    /**
     * Supprimer le panier actuel.
     *
     * @return void
     */
    public function destroy()
    {
        return $this->session->set('panier', []);
    }
}
