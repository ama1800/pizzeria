<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
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
    private $libelle;

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

    /**
     * @ORM\OneToMany(targetEntity=Quantite::class, mappedBy="menu")
     */
    private $quantites;

    /**
     * @ORM\Column(type="boolean")
     */
    private $disponible;

    /**
     * @ORM\OneToMany(targetEntity=ProduitsOnMenu::class, mappedBy="menu",cascade="all")
     */
    private $produitsOnMenus;

    public function __construct()
    {
        $this->produitsDuMenus = new ArrayCollection();
        $this->quantites = new ArrayCollection();
        $this->produitsOnMenus = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->getLibelle();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Collection|Quantite[]
     */
    public function getQuantites(): Collection
    {
        return $this->quantites;
    }

    public function addQuantite(Quantite $quantite): self
    {
        if (!$this->quantites->contains($quantite)) {
            $this->quantites[] = $quantite;
            $quantite->setMenu($this);
        }

        return $this;
    }

    public function removeQuantite(Quantite $quantite): self
    {
        if ($this->quantites->removeElement($quantite)) {
            // set the owning side to null (unless already changed)
            if ($quantite->getMenu() === $this) {
                $quantite->setMenu(null);
            }
        }

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

    /**
     * @return Collection|ProduitsOnMenu[]
     */
    public function getProduitsOnMenus(): Collection
    {
        return $this->produitsOnMenus;
    }

    public function addProduitsOnMenu(ProduitsOnMenu $produitsOnMenu): self
    {
        if (!$this->produitsOnMenus->contains($produitsOnMenu)) {
            $this->produitsOnMenus[] = $produitsOnMenu;
            $produitsOnMenu->setMenu($this);
        }

        return $this;
    }

    public function removeProduitsOnMenu(ProduitsOnMenu $produitsOnMenu): self
    {
        if ($this->produitsOnMenus->removeElement($produitsOnMenu)) {
            // set the owning side to null (unless already changed)
            if ($produitsOnMenu->getMenu() === $this) {
                $produitsOnMenu->setMenu(null);
            }
        }

        return $this;
    }
}
