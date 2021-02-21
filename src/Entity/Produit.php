<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProduitRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity(repositoryClass=ProduitRepository::class)
 */
class Produit
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $produitLibelle;

    /**
     * @ORM\Column(type="float")
     */
    private $ProduitPrix;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponible;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="produits",cascade="all")
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="produit",cascade={"persist"})
     */
    private $commentaires;

    /**
     * @ORM\OneToMany(targetEntity=Image::class, mappedBy="produit",cascade={"persist"})
     *  @Assert\Valid())
     */

    private $images;

    /**
     * @ORM\OneToMany(targetEntity=Recette::class, mappedBy="produit", orphanRemoval=true,cascade="all")
     */
    private $recettes;

    /**
     * @ORM\OneToMany(targetEntity=ProduitsOnMenu::class, mappedBy="produit",cascade={"persist"})
     */
    private $produitsOnMenu;

    /**
     * @ORM\OneToMany(targetEntity=CommandeProducts::class, mappedBy="produit",cascade={"persist"})
     */
    private $commandes;

    /**
     * @Gedmo\Slug(fields={"produitLibelle"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;


    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->recettes = new ArrayCollection();
        $this->produitsOnMenu = new ArrayCollection();
        $this->commandes = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->getProduitLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduitLibelle(): ?string
    {
        return $this->produitLibelle;
    }

    public function setProduitLibelle(string $produitLibelle): self
    {
        $this->produitLibelle = $produitLibelle;

        return $this;
    }

    public function getProduitPrix(): ?float
    {
        return $this->ProduitPrix;
    }

    public function setProduitPrix(float $ProduitPrix): self
    {
        $this->ProduitPrix = $ProduitPrix;

        return $this;
    }

    public function getDisponible(): ?bool
    {
        return $this->disponible;
    }

    public function setDisponible(bool $disponible): self
    {
        $this->disponible = $disponible;

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setProduit($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getProduit() === $this) {
                $commentaire->setProduit(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Image[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setProduit($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            if ($image->getProduit() === $this) {
                $image->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Recette[]
     */
    public function getRecettes(): Collection
    {
        return $this->recettes;
    }

    public function addRecette(Recette $recette): self
    {
        if (!$this->recettes->contains($recette)) {
            $this->recettes[] = $recette;
            $recette->setProduit($this);
        }

        return $this;
    }

    public function removeRecette(Recette $recette): self
    {
        if ($this->recettes->removeElement($recette)) {
            // set the owning side to null (unless already changed)
            if ($recette->getProduit() === $this) {
                $recette->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ProduitsOnMenu[]
     */
    public function getProduitsOnMenu(): Collection
    {
        return $this->produitsOnMenu;
    }

    public function addProduitsOnMenu(ProduitsOnMenu $produitsOnMenu): self
    {
        if (!$this->produitsOnMenu->contains($produitsOnMenu)) {
            $this->produitsOnMenu[] = $produitsOnMenu;
            $produitsOnMenu->setProduit($this);
        }

        return $this;
    }

    public function removeProduitsOnMenu(ProduitsOnMenu $produitsOnMenu): self
    {
        if ($this->produitsOnMenu->removeElement($produitsOnMenu)) {
            // set the owning side to null (unless already changed)
            if ($produitsOnMenu->getProduit() === $this) {
                $produitsOnMenu->setProduit(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CommandeProducts[]
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(CommandeProducts $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setProduit($this);
        }

        return $this;
    }

    public function removeCommande(CommandeProducts $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getProduit() === $this) {
                $commande->setProduit(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
