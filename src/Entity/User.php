<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $login;

    #[ORM\Column(type: 'string', length: 100)]
    private $password;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'string', length: 100)]
    private $first_name;

    #[ORM\Column(type: 'date')]
    private $birth_date;

    #[ORM\Column(type: 'boolean')]
    private $is_admin;

    #[ORM\Column(type: 'boolean')]
    private $is_super_admin;

    #[ORM\ManyToMany(targetEntity: ShoppingCart::class, mappedBy: 'id_user')]
    private $id_product;

    public function __construct()
    {
        $this->id_product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
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

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(\DateTimeInterface $birth_date): self
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getIsAdmin(): ?bool
    {
        return $this->is_admin;
    }

    public function setIsAdmin(bool $is_admin): self
    {
        $this->is_admin = $is_admin;

        return $this;
    }

    public function getIsSuperAdmin(): ?bool
    {
        return $this->is_super_admin;
    }

    public function setIsSuperAdmin(bool $is_super_admin): self
    {
        $this->is_super_admin = $is_super_admin;

        return $this;
    }

    /**
     * @return Collection<int, ShoppingCart>
     */
    public function getIdProduct(): Collection
    {
        return $this->id_product;
    }

    public function addIdProduct(ShoppingCart $idProduct): self
    {
        if (!$this->id_product->contains($idProduct)) {
            $this->id_product[] = $idProduct;
            $idProduct->addIdUser($this);
        }

        return $this;
    }

    public function removeIdProduct(ShoppingCart $idProduct): self
    {
        if ($this->id_product->removeElement($idProduct)) {
            $idProduct->removeIdUser($this);
        }

        return $this;
    }
}
