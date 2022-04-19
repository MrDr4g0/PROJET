<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'float')]
    private $price;

    #[ORM\Column(type: 'integer')]
    private $stock;

    #[ORM\OneToMany(mappedBy: 'id_product', targetEntity: ShoppingCart::class)]
    private $id_shopping_cart;

    public function __construct()
    {
        $this->id_shopping_cart = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCart>
     */
    public function getIdShoppingCart(): Collection
    {
        return $this->id_shopping_cart;
    }

    public function addIdShoppingCart(ShoppingCart $idShoppingCart): self
    {
        if (!$this->id_shopping_cart->contains($idShoppingCart)) {
            $this->id_shopping_cart[] = $idShoppingCart;
            $idShoppingCart->setIdProduct($this);
        }

        return $this;
    }

    public function removeIdShoppingCart(ShoppingCart $idShoppingCart): self
    {
        if ($this->id_shopping_cart->removeElement($idShoppingCart)) {
            // set the owning side to null (unless already changed)
            if ($idShoppingCart->getIdProduct() === $this) {
                $idShoppingCart->setIdProduct(null);
            }
        }

        return $this;
    }
}
