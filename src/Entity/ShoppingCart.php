<?php

namespace App\Entity;

use App\Repository\ShoppingCartRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShoppingCartRepository::class)]
class ShoppingCart
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'id_shopping_cart')]
    #[ORM\JoinColumn(nullable: false)]
    private $id_user;

    #[ORM\ManyToOne(targetEntity: Product::class, inversedBy: 'id_shopping_cart')]
    #[ORM\JoinColumn(nullable: false)]
    private $id_product;

    #[ORM\Column(type: 'integer')]
    private $nb_product;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->id_user;
    }

    public function setIdUser(?User $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->id_product;
    }

    public function setIdProduct(?Product $id_product): self
    {
        $this->id_product = $id_product;

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
