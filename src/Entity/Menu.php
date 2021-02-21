<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\MenuRepository;
use Gedmo\Mapping\Annotation as Gedmo;
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
     * @ORM\Column(type="boolean")
     */
    private $disponible;

    /**
     * @ORM\OneToMany(targetEntity=ProduitsOnMenu::class, mappedBy="menu",cascade={"persist"})
     */
    private $produitsOnMenu;

    /**
     * @Gedmo\Slug(fields={"libelle"})
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    public function __construct()
    {
        $this->produitsOnMenu = new ArrayCollection();
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
    public function getProduitsOnMenu(): Collection
    {
        return $this->produitsOnMenu;
    }

    public function addProduitsOnMenu(ProduitsOnMenu $produitsOnMenu): self
    {
        if (!$this->produitsOnMenu->contains($produitsOnMenu)) {
            $this->produitsOnMenu[] = $produitsOnMenu;
            $produitsOnMenu->setMenu($this);
        }

        return $this;
    }

    public function removeProduitsOnMenu(ProduitsOnMenu $produitsOnMenu): self
    {
        if ($this->produitsOnMenu->removeElement($produitsOnMenu)) {
            // set the owning side to null (unless already changed)
            if ($produitsOnMenu->getMenu() === $this) {
                $produitsOnMenu->setMenu(null);
            }
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }
}
