<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'id_product')]
    private $id_user;

    #[ORM\ManyToMany(targetEntity: product::class, inversedBy: 'shoppingCarts')]
    private $id_product;

    #[ORM\Column(type: 'integer')]
    private $nb_product;

    public function __construct()
    {
        $this->id_user = new ArrayCollection();
        $this->id_product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, user>
     */
    public function getIdUser(): Collection
    {
        return $this->id_user;
    }

    public function addIdUser(user $idUser): self
    {
        if (!$this->id_user->contains($idUser)) {
            $this->id_user[] = $idUser;
        }

        return $this;
    }

    public function removeIdUser(user $idUser): self
    {
        $this->id_user->removeElement($idUser);

        return $this;
    }

    /**
     * @return Collection<int, product>
     */
    public function getIdProduct(): Collection
    {
        return $this->id_product;
    }

    public function addIdProduct(product $idProduct): self
    {
        if (!$this->id_product->contains($idProduct)) {
            $this->id_product[] = $idProduct;
        }

        return $this;
    }

    public function removeIdProduct(product $idProduct): self
    {
        $this->id_product->removeElement($idProduct);

        return $this;
    }

    public function getNbProduct(): ?int
    {
        return $this->nb_product;
    }

    public function setNbProduct(int $nb_product): self
    {
        $this->nb_product = $nb_product;

        return $this;
    }
}
